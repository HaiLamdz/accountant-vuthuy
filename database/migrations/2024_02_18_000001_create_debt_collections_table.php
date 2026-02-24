<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('debt_collections', function (Blueprint $table) {
            $table->id();
            $table->string('ho_ten');
            $table->string('so_quay');
            $table->decimal('so_tien', 15, 2);
            $table->integer('thang'); // 1-12
            $table->integer('nam');
            $table->date('ngay_thu_du_kien'); // Ngày dự kiến thu
            $table->enum('trang_thai', ['chua_thu', 'da_thu'])->default('chua_thu');
            $table->date('ngay_thu_thuc_te')->nullable(); // Ngày thực tế đã thu
            $table->timestamps();
            
            $table->index(['thang', 'nam', 'trang_thai']);
            $table->index('ngay_thu_du_kien');
            $table->index('ngay_thu_thuc_te');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('debt_collections');
    }
};
