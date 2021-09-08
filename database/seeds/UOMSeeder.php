<?php

use Illuminate\Database\Seeder;

class UOMSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('unit_of_measurements')->insert([
        	[
        		'code' => 'M',
        		'name' => 'Meter(s)',
        		'description' => 'Meter(s)',
        	],
        	[
        		'code' => 'PC',
        		'name' => 'Piece(s)',
        		'description' => 'Piece(s)',
        	],
        	[
        		'code' => 'Set',
        		'name' => 'Set',
        		'description' => 'Set',
        	],
            [
                'code' => 'Unit',
                'name' => 'Unit',
                'description' => 'Unit',
            ],
            [
                'code' => 'Bag',
                'name' => 'Bag(s)',
                'description' => 'Bag',
            ],
            [
                'code' => 'Book',
                'name' => 'Book(s)',
                'description' => 'Unit',
            ],
            [
                'code' => 'Booklet',
                'name' => 'Booklet(s)',
                'description' => 'Booklet(s)',
            ],
            [
                'code' => 'Box',
                'name' => 'Box(es)',
                'description' => 'Box(es)',
            ],
            [
                'code' => 'Btl',
                'name' => 'Bottle(s)',
                'description' => 'Bottle(s)',
            ],
            [
                'code' => 'Bundle',
                'name' => 'Bundle(s)',
                'description' => 'Bundle(s)',
            ],
            [
                'code' => 'Can',
                'name' => 'Can(s)',
                'description' => 'Can(s)',
            ],
            [
                'code' => 'Case',
                'name' => 'Case(s)',
                'description' => 'Case(s)',
            ],
            [
                'code' => 'm3',
                'name' => 'Cubic Meter(s)',
                'description' => 'Cubic Meter(s)',
            ],
            [
                'code' => 'Dose',
                'name' => 'Dose(s)',
                'description' => 'Dose(s)',
            ],
            [
                'code' => 'Dozen',
                'name' => 'Dozen(s)',
                'description' => 'Dozen(s)',
            ],
            [
                'code' => 'Drum',
                'name' => 'Drum(s)',
                'description' => 'Drum(s)',
            ],
            [
                'code' => 'ft',
                'name' => 'Feet',
                'description' => 'Feet',
            ],
            [
                'code' => 'gal',
                'name' => 'Gallon(s)',
                'description' => 'Gallon(s)',
            ],
            [
                'code' => 'gm',
                'name' => 'Gram(s)',
                'description' => 'Gram(s)',
            ],
            [
                'code' => 'Head',
                'name' => 'Head(s)',
                'description' => 'Head(s)',
            ],
            [
                'code' => 'kg',
                'name' => 'Kilogram(s)',
                'description' => 'Kilogram(s)',
            ],
            [
                'code' => 'Kit',
                'name' => 'Kit(s)',
                'description' => 'Kit(s)',
            ],
            [
                'code' => 'L',
                'name' => 'Litter(s)',
                'description' => 'Litter(s)',
            ],
            [
                'code' => 'mg',
                'name' => 'Miligram(s)',
                'description' => 'Miligram(s)',
            ],
            [
                'code' => 'Pack',
                'name' => 'Pack(s)',
                'description' => 'Pack(s)',
            ],
            [
                'code' => 'Pad',
                'name' => 'Pad(s)',
                'description' => 'Pad(s)',
            ],
            [
                'code' => 'Pail',
                'name' => 'Pail(s)',
                'description' => 'Pail(s)',
            ],
            [
                'code' => 'Pair',
                'name' => 'Pair(s)',
                'description' => 'Pair(s)',
            ],
            [
                'code' => 'Rim',
                'name' => 'Rim(s)',
                'description' => 'Rim(s)',
            ],
            [
                'code' => 'Roll',
                'name' => 'Roll(s)',
                'description' => 'Roll(s)',
            ],
            [
                'code' => 'Sachet',
                'name' => 'Sachet(s)',
                'description' => 'Sachet(s)',
            ],
            [
                'code' => 'Sack',
                'name' => 'Sack(s)',
                'description' => 'Sack(s)',
            ],
            [
                'code' => 'm2',
                'name' => 'Square Meter(s)',
                'description' => 'Square Meter(s)',
            ],
            [
                'code' => 'Tank',
                'name' => 'Tank(s)',
                'description' => 'Tank(s)',
            ],
            [
                'code' => 'Tie',
                'name' => 'Tie',
                'description' => 'Tie',
            ],
            [
                'code' => 'Ton',
                'name' => 'Ton(s)',
                'description' => 'Ton(s)',
            ],
            [
                'code' => 'Tray',
                'name' => 'Tray(s)',
                'description' => 'Tray(s)',
            ],
            [
                'code' => 'Tube',
                'name' => 'Tube(s)',
                'description' => 'Tube(s)',
            ],
            [
                'code' => 'Vial',
                'name' => 'Vial(s)',
                'description' => 'Vial(s)',
            ],
            [
                'code' => 'Yard',
                'name' => 'Yard(s)',
                'description' => 'Yard(s)',
            ],
        ]);
    }
}
