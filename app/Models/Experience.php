<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    protected $fillable = [
        'role',
        'company',
        'location',
        'period_from',
        'period_to',
        'is_current',
        'description',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'description' => 'array',
        'is_current' => 'boolean',
        'is_active' => 'boolean',
    ];
    
    public function setDescriptionAttribute($value)
    {
        if (is_array($value) && isset($value[0]['item'])) {
            // Convert from Repeater format to simple array
            $this->attributes['description'] = json_encode(array_map(fn($item) => $item['item'], $value));
        } else {
            $this->attributes['description'] = is_array($value) ? json_encode($value) : $value;
        }
    }
    
    public function getDescriptionAttribute($value)
    {
        $decoded = json_decode($value, true) ?? [];
        
        // If it's already in the repeater format, return as is
        if (isset($decoded[0]['item'])) {
            return $decoded;
        }
        
        // Convert simple array to repeater format for Filament
        return array_map(fn($item) => ['item' => $item], $decoded);
    }

    public function getPeriodAttribute()
    {
        $from = $this->period_from;
        $to = $this->is_current ? 'Present' : $this->period_to;
        return "{$from} - {$to}";
    }
}
