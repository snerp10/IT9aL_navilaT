<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Inventory;
use Carbon\Carbon;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create product categories
        $categories = [
            ['category_name' => 'Restorative Materials', 'description' => 'Materials used for dental restorations like fillings'],
            ['category_name' => 'Preventive Products', 'description' => 'Products used for preventive dental care'],
            ['category_name' => 'Endodontic Materials', 'description' => 'Materials for root canal procedures'],
            ['category_name' => 'Impression Materials', 'description' => 'Materials used to make dental impressions'],
            ['category_name' => 'Oral Surgery Supplies', 'description' => 'Supplies used in dental surgeries'],
            ['category_name' => 'Orthodontic Supplies', 'description' => 'Supplies used in orthodontic treatments'],
            ['category_name' => 'Infection Control', 'description' => 'Products for sterilization and infection control'],
            ['category_name' => 'Disposables', 'description' => 'Single-use items for dental procedures'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        // Array of dental products by category
        $productsByCategory = [
            'Restorative Materials' => [
                [
                    'product_name' => 'Composite Resin (A2 Shade)',
                    'description' => 'Light-cured composite resin for anterior and posterior restorations',
                    'cost_price' => 25.75,
                    'selling_price' => 45.50,
                    'expiration_date' => Carbon::now()->addYear()
                ],
                [
                    'product_name' => 'Dental Amalgam Capsules',
                    'description' => 'Pre-dosed amalgam capsules for posterior restorations',
                    'cost_price' => 18.25,
                    'selling_price' => 32.99,
                    'expiration_date' => Carbon::now()->addMonths(18)
                ],
                [
                    'product_name' => 'Glass Ionomer Cement',
                    'description' => 'Self-curing glass ionomer for restorations and bases',
                    'cost_price' => 29.50,
                    'selling_price' => 49.95,
                    'expiration_date' => Carbon::now()->addMonths(12)
                ],
                [
                    'product_name' => 'Bonding Agent',
                    'description' => 'Dental adhesive for composite restorations',
                    'cost_price' => 32.00,
                    'selling_price' => 58.50,
                    'expiration_date' => Carbon::now()->addMonths(14)
                ]
            ],
            'Preventive Products' => [
                [
                    'product_name' => 'Fluoride Varnish',
                    'description' => '5% sodium fluoride varnish for cavity prevention',
                    'cost_price' => 15.50,
                    'selling_price' => 28.99,
                    'expiration_date' => Carbon::now()->addMonths(20)
                ],
                [
                    'product_name' => 'Pit and Fissure Sealant',
                    'description' => 'Light-cured resin sealant for cavity prevention',
                    'cost_price' => 22.75,
                    'selling_price' => 39.95,
                    'expiration_date' => Carbon::now()->addMonths(16)
                ],
                [
                    'product_name' => 'Prophy Paste',
                    'description' => 'Medium grit prophylaxis paste for cleaning teeth',
                    'cost_price' => 10.25,
                    'selling_price' => 19.50,
                    'expiration_date' => Carbon::now()->addMonths(24)
                ]
            ],
            'Endodontic Materials' => [
                [
                    'product_name' => 'Gutta Percha Points',
                    'description' => 'Standard size gutta percha points for root canal filling',
                    'cost_price' => 16.50,
                    'selling_price' => 29.99,
                    'expiration_date' => Carbon::now()->addYears(3)
                ],
                [
                    'product_name' => 'Root Canal Sealer',
                    'description' => 'Epoxy resin based root canal sealer',
                    'cost_price' => 35.25,
                    'selling_price' => 62.50,
                    'expiration_date' => Carbon::now()->addMonths(18)
                ],
                [
                    'product_name' => 'Endodontic Files',
                    'description' => 'Stainless steel K-files for root canal procedures',
                    'cost_price' => 28.00,
                    'selling_price' => 45.95,
                    'expiration_date' => Carbon::now()->addYears(5)
                ]
            ],
            'Impression Materials' => [
                [
                    'product_name' => 'Alginate Impression Material',
                    'description' => 'Fast-setting alginate for preliminary impressions',
                    'cost_price' => 14.75,
                    'selling_price' => 25.99,
                    'expiration_date' => Carbon::now()->addMonths(12)
                ],
                [
                    'product_name' => 'Polyvinyl Siloxane (PVS)',
                    'description' => 'Addition silicone for precision impressions',
                    'cost_price' => 45.50,
                    'selling_price' => 78.95,
                    'expiration_date' => Carbon::now()->addMonths(24)
                ],
                [
                    'product_name' => 'Impression Trays',
                    'description' => 'Disposable plastic impression trays (assorted sizes)',
                    'cost_price' => 8.25,
                    'selling_price' => 15.50,
                    'expiration_date' => Carbon::now()->addYears(5)
                ]
            ],
            'Oral Surgery Supplies' => [
                [
                    'product_name' => 'Surgical Sutures',
                    'description' => '4-0 silk sutures for oral surgery',
                    'cost_price' => 24.50,
                    'selling_price' => 42.99,
                    'expiration_date' => Carbon::now()->addYears(2)
                ],
                [
                    'product_name' => 'Hemostatic Agent',
                    'description' => 'Topical hemostatic agent for controlling bleeding',
                    'cost_price' => 32.75,
                    'selling_price' => 58.95,
                    'expiration_date' => Carbon::now()->addMonths(18)
                ],
                [
                    'product_name' => 'Surgical Blades',
                    'description' => 'Sterile surgical blades #15',
                    'cost_price' => 12.25,
                    'selling_price' => 22.50,
                    'expiration_date' => Carbon::now()->addYears(3)
                ]
            ],
            'Orthodontic Supplies' => [
                [
                    'product_name' => 'Orthodontic Brackets',
                    'description' => 'Metal brackets for orthodontic treatment',
                    'cost_price' => 85.50,
                    'selling_price' => 150.00,
                    'expiration_date' => Carbon::now()->addYears(5)
                ],
                [
                    'product_name' => 'Orthodontic Wire',
                    'description' => 'Stainless steel archwires (16 gauge)',
                    'cost_price' => 34.25,
                    'selling_price' => 62.95,
                    'expiration_date' => Carbon::now()->addYears(5)
                ],
                [
                    'product_name' => 'Elastic Ligatures',
                    'description' => 'Colored elastic ligatures for braces',
                    'cost_price' => 10.75,
                    'selling_price' => 19.99,
                    'expiration_date' => Carbon::now()->addYears(3)
                ]
            ],
            'Infection Control' => [
                [
                    'product_name' => 'Surface Disinfectant',
                    'description' => 'Hospital-grade surface disinfectant spray',
                    'cost_price' => 12.50,
                    'selling_price' => 22.95,
                    'expiration_date' => Carbon::now()->addYears(2)
                ],
                [
                    'product_name' => 'Autoclave Bags',
                    'description' => 'Self-sealing sterilization pouches',
                    'cost_price' => 15.25,
                    'selling_price' => 27.50,
                    'expiration_date' => Carbon::now()->addYears(5)
                ],
                [
                    'product_name' => 'Hand Sanitizer',
                    'description' => '70% alcohol hand sanitizer (1L)',
                    'cost_price' => 8.75,
                    'selling_price' => 16.99,
                    'expiration_date' => Carbon::now()->addYears(2)
                ]
            ],
            'Disposables' => [
                [
                    'product_name' => 'Dental Bibs',
                    'description' => 'Disposable patient bibs with clips',
                    'cost_price' => 9.50,
                    'selling_price' => 18.95,
                    'expiration_date' => Carbon::now()->addYears(5)
                ],
                [
                    'product_name' => 'Cotton Rolls',
                    'description' => 'Absorbent cotton rolls (medium)',
                    'cost_price' => 6.25,
                    'selling_price' => 12.50,
                    'expiration_date' => Carbon::now()->addYears(3)
                ],
                [
                    'product_name' => 'Saliva Ejectors',
                    'description' => 'Disposable clear plastic saliva ejectors',
                    'cost_price' => 5.75,
                    'selling_price' => 11.99,
                    'expiration_date' => Carbon::now()->addYears(4)
                ],
                [
                    'product_name' => 'Examination Gloves (Nitrile)',
                    'description' => 'Powder-free nitrile examination gloves (Box of 100)',
                    'cost_price' => 18.50,
                    'selling_price' => 32.95,
                    'expiration_date' => Carbon::now()->addYears(2)
                ],
                [
                    'product_name' => 'Face Masks',
                    'description' => 'Level 3 Surgical Face Masks (Box of 50)',
                    'cost_price' => 15.75,
                    'selling_price' => 29.99,
                    'expiration_date' => Carbon::now()->addYears(3)
                ]
            ]
        ];

        // Create suppliers mapping to categories
        $supplierSpecialties = [
            'Dental Solutions Inc.' => ['Restorative Materials', 'Preventive Products'],
            'OrthoTech Supplies' => ['Orthodontic Supplies'],
            'SurgicalDent Pro' => ['Oral Surgery Supplies'],
            'Endo Specialists Co.' => ['Endodontic Materials'],
            'ImpressionTech Systems' => ['Impression Materials'],
            'MediClean Supplies' => ['Infection Control', 'Disposables'],
        ];

        // Create products with initial inventory and link to suppliers
        foreach ($productsByCategory as $categoryName => $products) {
            $category = Category::where('category_name', $categoryName)->first();
            
            // Find suppliers that supply this category
            $relevantSuppliers = [];
            foreach ($supplierSpecialties as $supplierName => $categories) {
                if (in_array($categoryName, $categories)) {
                    $supplier = Supplier::where('name', $supplierName)->first();
                    if ($supplier) {
                        $relevantSuppliers[] = $supplier;
                    }
                }
            }
            
            if ($category) {
                foreach ($products as $productData) {
                    // Create the product - remove sku field from being inserted
                    $product = new Product([
                        'product_name' => $productData['product_name'],
                        'description' => $productData['description'],
                        'cost_price' => $productData['cost_price'],
                        'selling_price' => $productData['selling_price'],
                        'expiration_date' => $productData['expiration_date'],
                        'category_id' => $category->category_id
                    ]);
                    
                    $product->save();
                    
                    // Create inventory for the product
                    // Add some out-of-stock products (about 20% chance)
                    $initialQuantity = rand(0, 100) < 20 ? 0 : rand(20, 100);
                    $reorderLevel = rand(5, 15); // Set a reasonable reorder level for each product
                    Inventory::create([
                        'product_id' => $product->product_id,
                        'quantity' => $initialQuantity,
                        'reorder_level' => $reorderLevel,
                        'stock_status' => $initialQuantity > 0 ? ($initialQuantity <= $reorderLevel ? 'Low Stock' : 'Stock In') : 'Stock Out',
                        'last_updated' => now(),
                    ]);
                    
                    // Link product to relevant suppliers
                    foreach ($relevantSuppliers as $supplier) {
                        $product->suppliers()->attach($supplier->supplier_id, [
                            'date_supplied' => Carbon::now()->subDays(rand(1, 60)),
                        ]);
                    }
                }
            }
        }
        
        $this->command->info('Dental clinic products seeded successfully with supplier connections!');
    }
}