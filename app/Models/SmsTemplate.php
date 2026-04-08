<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsTemplate extends Model
{
    use HasFactory;

    protected $table = 'sms_templates';

    protected $fillable = [
        'key',
        'template_name',
        'template',
        'status',
        'business_entity_id',
        'client_id',
        'event_id',
        'exclude_notify',
    ];

    protected $casts = [
        'exclude_notify' => 'array',
    ];

    // ── Helpers ────────────────────────────────────────────────
    public static function getByKey(string $key): ?self
    {
        return self::where('key', $key)
                   ->where('status', 'Active')
                   ->first();
    }

    public function render(array $variables): string
    {
        return str_replace(
            array_map(fn($k) => '{{' . $k . '}}', array_keys($variables)),
            array_values($variables),
            $this->template
        );
    }
}