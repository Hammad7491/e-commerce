<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('products')
            ->select(['id', 'name'])
            ->whereNull('slug')
            ->orWhere('slug', '')
            ->orderBy('id')
            ->chunkById(200, function ($products) {
                foreach ($products as $p) {
                    $base = Str::slug($p->name ?? '');
                    $base = $base !== '' ? $base : 'product';

                    $slug = $base;
                    $i = 2;

                    while (
                        DB::table('products')
                            ->where('id', '!=', $p->id)
                            ->where('slug', $slug)
                            ->exists()
                    ) {
                        $slug = $base . '-' . $i;
                        $i++;
                    }

                    DB::table('products')->where('id', $p->id)->update(['slug' => $slug]);
                }
            });
    }

    public function down(): void
    {
        // No-op: keep generated slugs.
    }
};

