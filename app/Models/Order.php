<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'order_number',
        'total_price',
        'total_kdv',
        'status',
        'name',
        'phone',
        'email',
        'address',
        'city',
        'district',
        'zip_code',
        'note',
        'payment_method',
        'cargo_company',
        'cargo_tracking_number',
        'payment_receipt',
        'payment_status',
        'admin_note',
        'shipped_at',
        'delivered_at',
        'requires_approval',
        'admin_approved',
        'approved_at',
        'tc_kimlik',
        'dogum_tarihi',
    ];

    protected $casts = [
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'approved_at' => 'datetime',
        'dogum_tarihi' => 'date',
        'requires_approval' => 'boolean',
        'admin_approved' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function refund()
    {
        return $this->hasOne(Refund::class);
    }

    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'pending' => 'Beklemede',
            'processing' => 'Hazırlanıyor',
            'shipped' => 'Kargoda',
            'delivered' => 'Teslim Edildi',
            'cancelled' => 'İptal Edildi',
            'refunded' => 'İade Edildi',
            default => 'Bilinmiyor',
        };
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending' => 'warning',
            'processing' => 'info',
            'shipped' => 'primary',
            'delivered' => 'success',
            'cancelled' => 'danger',
            'refunded' => 'secondary',
            default => 'secondary',
        };
    }

    public function getPaymentStatusLabelAttribute()
    {
        return match($this->payment_status) {
            'pending' => 'Beklemede',
            'confirmed' => 'Onaylandı',
            'rejected' => 'Reddedildi',
            default => 'Bilinmiyor',
        };
    }

    public function getPaymentStatusColorAttribute()
    {
        return match($this->payment_status) {
            'pending' => 'warning',
            'confirmed' => 'success',
            'rejected' => 'danger',
            default => 'secondary',
        };
    }

    public function canBeCancelled()
    {
        return $this->status === 'pending';
    }

    public function canBeRefunded()
    {
        return $this->status === 'delivered' && !$this->refund;
    }

    public function requiresAdminApproval()
    {
        return $this->requires_approval && !$this->admin_approved;
    }
}
