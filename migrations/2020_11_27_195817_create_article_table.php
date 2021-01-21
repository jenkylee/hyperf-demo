<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateArticleTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('article')) {
            Schema::create('article', function (Blueprint $table) {
                $table->integerIncrements('id')->comment('ID');
                $table->string('name', 64)->default('')->comment('文章标题');
                $table->string('sub_name', 64)->default('')->comment('文章副标题');
                $table->string('hotfile', 255)->default('')->comment('封面图片');
                $table->longText('content')->comment('文章html内容');
                $table->string('author', 32)->default('')->comment('作者');
                $table->tinyInteger('category_type')->default(1)->comment('分类类别：1-心理成长');
                $table->tinyInteger('status')->default(1)->comment('是否有效：0-无效，1-有效');
                $table->string('creator', 32)->default('')->comment('创建人');
                $table->string('last_modifier', 32)->default('')->comment('修改人');
                $table->dateTime('created_at')->nullable()->comment('创建时间');
                $table->dateTime('updated_at')->nullable()->comment('更新时间');

                $table->index('category_type', 'idx_category_type');
                $table->index('status', 'idx_status');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article');
    }
}
