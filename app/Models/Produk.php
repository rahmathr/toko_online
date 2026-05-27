<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terkait dengan model.
     *
     * @var string
     */
    protected $table = 'produk';

    /**
     * Attributes yang tidak boleh di-mass assignment.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * Relasi ke tabel kategori (Many-to-One).
     * Sebuah produk dimiliki oleh satu kategori.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    /**
     * Relasi ke tabel user (Many-to-One).
     * Sebuah produk dimiliki oleh satu user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke tabel foto_produk (One-to-Many).
     * Sebuah produk dapat memiliki banyak foto.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function fotoProduk()
    {
        return $this->hasMany(FotoProduk::class);
    }
}
