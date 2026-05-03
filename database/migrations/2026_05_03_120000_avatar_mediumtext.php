<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Ampliamos a MEDIUMTEXT para guardar la foto como base64
        DB::statement('ALTER TABLE usuarios MODIFY COLUMN avatar MEDIUMTEXT NULL');
        DB::statement('ALTER TABLE hijos    MODIFY COLUMN avatar MEDIUMTEXT NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE usuarios MODIFY COLUMN avatar VARCHAR(500) NULL');
        DB::statement('ALTER TABLE hijos    MODIFY COLUMN avatar VARCHAR(255) NULL');
    }
};
