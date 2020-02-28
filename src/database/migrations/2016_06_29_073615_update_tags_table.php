<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateTagsTable extends Migration {

	public function up()
	{

		Schema::table('tagging_tags', function ($table) {
			$table->integer('tag_group_id')->unsigned()->nullable()->after('id');
			$table->foreign('tag_group_id')->references('id')->on('tagging_tag_groups');
		});

	}


	public function down()
	{
		if (DB::getDriverName() !== 'sqlite') { // Keep just this // https://github.com/laravel/framework/issues/23461
			Schema::table('tagging_tags', function ($table) {
				$table->dropForeign('tagging_tags_tag_group_id_foreign');
				$table->dropColumn('tag_group_id');
			});
		}	
	}
}
