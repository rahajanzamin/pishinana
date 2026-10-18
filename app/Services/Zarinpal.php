<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;

/**
 * درگاه زرین‌پال — نسخه‌ی ۴ (REST)
 *
 * اگر ZARINPAL_MERCHANT_ID در .env خالی باشد، به‌جای درگاه واقعی
 * «درگاه شبیه‌سازی‌شده» نمایش داده می‌شود تا سایت برای دمو کار کند.
 * کافی است کلید درگاه را بگذاری تا پرداخت واقعی فعال شود.
 */
class Zarinpal
{
    private function isConfigured(): bool
    {
        return (bool) config('services.zarinpal.merchant_id');
    }

    private function baseUrl(): string
    {
        return config('services.zarinpal.sandbox')
            ? 'https://sandbox.zarinpal.com/pg/v4'
            : 'https://payment.zarinpal.com/pg/v4';
    }

    private function startPayUrl(string $authority): string
    {
        $base = config('services.zarinpal.sandbox')
            ? 'https://sandbox.zarinpal.com/pg/StartPay/'
            : 'https://payment.zarinpal.com/pg/StartPay/';

        return $base . $authority;
    }

    /** آیا درگاه واقعی تنظیم شده؟ */
    public function enabled(): bool
    {
        return $this->isConfigured();
    }

    /**
     * شروع پرداخت — آدرس ریدایرکت به درگاه را برمی‌گرداند.
     */
    public function requestPayment(Order $order, string $callbackUrl): string
    {
        if (! $this->isConfigured()) {
            // حالت دمو → درگاه شبیه‌سازی‌شده
            return route('payment.fake', $order);
        }

        $response = Http::withHeaders([
            'accept' => 'application/json',
            'content-type' => 'application/json',
        ])->post($this->baseUrl() . '/payment/request.json', [
            'merchant_id' => config('services.zarinpal.merchant_id'),
            'amount' => $order->total,           // به تومان
            'currency' => 'IRT',
            'description' => "سفارش {$order->order_number} | PISHINANA",
            'callback_url' => $callbackUrl,
        ]);

        $authority = $response->json('data.authority');

        if (! $authority) {
            abort(500, 'ارتباط با درگاه پرداخت برقرار نشد: ' . ($response->json('errors') ?? 'خطای ناشناخته'));
        }

        $order->update(['payment_authority' => $authority]);

        return $this->startPayUrl($authority);
    }

    /**
     * تأیید پرداخت پس از بازگشت از درگاه.
     *
     * @return array{success: bool, ref_id: ?string, message: string}
     */
    public function verify(Order $order, string $authority): array
    {
        // حالت دمو
        if (! $this->isConfigured()) {
            $ok = str_starts_with($authority, 'DEMO-');
            return [
                'success' => $ok,
                'ref_id' => $ok ? 'DEMO-' . random_int(100000, 999999) : null,
                'message' => $ok ? 'پرداخت آزمایشی با موفقیت انجام شد.' : 'پرداخت آزمایشی ناموفق بود.',
            ];
        }

        $response = Http::withHeaders([
            'accept' => 'application/json',
            'content-type' => 'application/json',
        ])->post($this->baseUrl() . '/payment/verify.json', [
            'merchant_id' => config('services.zarinpal.merchant_id'),
            'amount' => $order->total,
            'authority' => $authority,
        ]);

        $code = (int) $response->json('data.code');

        if (in_array($code, [100, 101], true)) {
            return [
                'success' => true,
                'ref_id' => (string) $response->json('data.ref_id'),
                'message' => 'پرداخت با موفقیت انجام شد.',
            ];
        }

        return [
            'success' => false,
            'ref_id' => null,
            'message' => 'پرداخت تأیید نشد (کد ' . $code . ').',
        ];
    }
}
