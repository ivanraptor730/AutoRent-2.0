<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateListingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('listings', function(Blueprint $table)
		{
			$table->bigInteger('listing_id', true)->unsigned();
			$table->string('listing_title', 50);
			$table->string('destinations', 125);
			$table->float('rate', 10, 0);
			$table->bigInteger('driver_id')->unsigned()->index('driver_id');
			$table->bigInteger('car_id')->unsigned()->index('car_id');
			$table->string('notes');
			$table->boolean('listing_status');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('listings');
	}

}
