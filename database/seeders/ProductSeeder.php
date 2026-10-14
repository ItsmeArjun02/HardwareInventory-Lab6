<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name' => 'Portland Cement 40kg',
                'sku' => 'CEM-001',
                'description' => 'General purpose Portland cement.',
                'category' => 'Hardware',
                'quantity' => 8,
                'reorder_level' => 10,
                'unit_price' => 285.00,
                'supplier' => 'ABC Construction Supply',
            ],
            [
                'name' => 'Marine Plywood 3/4',
                'sku' => 'LUM-001',
                'description' => 'Durable plywood for construction use.',
                'category' => 'Lumber',
                'quantity' => 20,
                'reorder_level' => 5,
                'unit_price' => 1250.00,
                'supplier' => 'Southwood Lumber',
            ],
            [
                'name' => '2x4 Lumber 10ft',
                'sku' => 'LUM-002',
                'description' => 'Standard construction lumber.',
                'category' => 'Lumber',
                'quantity' => 35,
                'reorder_level' => 10,
                'unit_price' => 220.00,
                'supplier' => 'Southwood Lumber',
            ],
            [
                'name' => 'Claw Hammer',
                'sku' => 'TOOL-001',
                'description' => 'Steel claw hammer with rubber grip.',
                'category' => 'Tools',
                'quantity' => 12,
                'reorder_level' => 5,
                'unit_price' => 350.00,
                'supplier' => 'Pro Tools Supply',
            ],
            [
                'name' => 'Adjustable Wrench',
                'sku' => 'TOOL-002',
                'description' => 'Adjustable wrench for general repair work.',
                'category' => 'Tools',
                'quantity' => 4,
                'reorder_level' => 5,
                'unit_price' => 280.00,
                'supplier' => 'Pro Tools Supply',
            ],
            [
                'name' => 'Screwdriver Set',
                'sku' => 'TOOL-003',
                'description' => 'Multi-piece screwdriver set.',
                'category' => 'Tools',
                'quantity' => 15,
                'reorder_level' => 5,
                'unit_price' => 450.00,
                'supplier' => 'Pro Tools Supply',
            ],
            [
                'name' => 'Electrical Wire 2.0mm',
                'sku' => 'ELEC-001',
                'description' => 'Electrical copper wire roll.',
                'category' => 'Electrical',
                'quantity' => 18,
                'reorder_level' => 6,
                'unit_price' => 950.00,
                'supplier' => 'Brightline Electrical',
            ],
            [
                'name' => 'LED Bulb 12W',
                'sku' => 'ELEC-002',
                'description' => 'Energy-saving LED bulb.',
                'category' => 'Electrical',
                'quantity' => 30,
                'reorder_level' => 10,
                'unit_price' => 120.00,
                'supplier' => 'Brightline Electrical',
            ],
            [
                'name' => 'PVC Pipe 1/2 inch',
                'sku' => 'PLUMB-001',
                'description' => 'PVC pipe for residential plumbing.',
                'category' => 'Plumbing',
                'quantity' => 25,
                'reorder_level' => 8,
                'unit_price' => 150.00,
                'supplier' => 'Flowrite Plumbing Supply',
            ],
            [
                'name' => 'PVC Elbow 1/2 inch',
                'sku' => 'PLUMB-002',
                'description' => 'PVC elbow fitting.',
                'category' => 'Plumbing',
                'quantity' => 6,
                'reorder_level' => 8,
                'unit_price' => 35.00,
                'supplier' => 'Flowrite Plumbing Supply',
            ],
            [
                'name' => 'White Latex Paint 4L',
                'sku' => 'PAINT-001',
                'description' => 'Interior white latex paint.',
                'category' => 'Paint',
                'quantity' => 10,
                'reorder_level' => 4,
                'unit_price' => 780.00,
                'supplier' => 'ColorMax Paint Center',
            ],
            [
                'name' => 'Red Enamel Paint 1L',
                'sku' => 'PAINT-002',
                'description' => 'Glossy red enamel paint.',
                'category' => 'Paint',
                'quantity' => 3,
                'reorder_level' => 5,
                'unit_price' => 320.00,
                'supplier' => 'ColorMax Paint Center',
            ],
            [
                'name' => 'Common Nails 2 inch',
                'sku' => 'HARD-001',
                'description' => 'General purpose steel nails.',
                'category' => 'Hardware',
                'quantity' => 40,
                'reorder_level' => 10,
                'unit_price' => 85.00,
                'supplier' => 'ABC Construction Supply',
            ],
            [
                'name' => 'Door Hinges Pair',
                'sku' => 'HARD-002',
                'description' => 'Heavy-duty metal door hinges.',
                'category' => 'Hardware',
                'quantity' => 7,
                'reorder_level' => 5,
                'unit_price' => 160.00,
                'supplier' => 'ABC Construction Supply',
            ],
            [
                'name' => 'Safety Gloves',
                'sku' => 'HARD-003',
                'description' => 'Protective gloves for construction work.',
                'category' => 'Hardware',
                'quantity' => 0,
                'reorder_level' => 5,
                'unit_price' => 95.00,
                'supplier' => 'ABC Construction Supply',
            ],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(
                ['sku' => $product['sku']],
                $product
            );
        }
    }
}