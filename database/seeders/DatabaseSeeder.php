<?php

namespace Database\Seeders;

use App\Models\Budget;
use App\Models\BudgetTransaction;
use App\Models\Estimation;
use App\Models\EstimationItem;
use App\Models\EstimationTemplate;
use App\Models\EstimationTemplateItem;
use App\Models\Foreman;
use App\Models\LaborLog;
use App\Models\Material;
use App\Models\MaterialRequest;
use App\Models\MaterialRequestItem;
use App\Models\Project;
use App\Models\Timeline;
use App\Models\User;
use App\Models\WorkType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── 1. Roles & Permissions ─────────────────────────────────────────────
        $saasAdminRole   = Role::firstOrCreate(['name' => 'saas_admin',   'guard_name' => 'web']);
        $tenantAdminRole = Role::firstOrCreate(['name' => 'tenant_admin', 'guard_name' => 'web']);
        $adminRole       = Role::firstOrCreate(['name' => 'admin',        'guard_name' => 'web']);
        $foremanRole     = Role::firstOrCreate(['name' => 'foreman',      'guard_name' => 'web']);
        $workerRole      = Role::firstOrCreate(['name' => 'worker',       'guard_name' => 'web']);

        $permissions = [
            'view-projects', 'create-projects', 'edit-projects', 'delete-projects',
            'approve-estimations', 'approve-material-requests',
            'view-budgets', 'edit-budgets', 'record-transactions',
            'view-materials', 'manage-materials',
            'view-labor-logs', 'create-labor-logs',
            'manage-users', 'manage-tenants',
        ];
        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        $saasAdminRole->syncPermissions($permissions);
        $tenantAdminRole->syncPermissions([
            'view-projects', 'create-projects', 'edit-projects',
            'approve-estimations', 'approve-material-requests',
            'view-budgets', 'edit-budgets', 'record-transactions',
            'view-materials', 'manage-materials',
            'view-labor-logs', 'create-labor-logs',
            'manage-users',
        ]);
        $adminRole->syncPermissions($permissions);
        $foremanRole->syncPermissions([
            'view-projects', 'view-budgets', 'view-materials',
            'view-labor-logs', 'create-labor-logs',
            'approve-material-requests',
        ]);
        $workerRole->syncPermissions(['view-projects', 'view-labor-logs', 'create-labor-logs']);

        // ── 2. Work Types (Trilingual: EN / AM / SO) ───────────────────────────
        $workTypesData = [
            ['name_en' => 'Excavation & Earthwork',  'name_am' => 'መቆፈር እና የመሬት ሥራ', 'name_so' => 'Qodista & Dhulka',    'category' => 'civil',      'unit' => 'm³',  'default_unit_rate_etb' => 280],
            ['name_en' => 'Reinforced Concrete C-25','name_am' => 'የተጠናከረ ኮንክሪት C-25', 'name_so' => 'Shubka C-25',         'category' => 'civil',      'unit' => 'm³',  'default_unit_rate_etb' => 9200],
            ['name_en' => 'HCB Block Masonry (20cm)','name_am' => 'የብሎክ ግንበኝነት (20ሳ.ሜ)', 'name_so' => 'Dhisidda Block (20cm)','category' => 'civil',    'unit' => 'm²',  'default_unit_rate_etb' => 850],
            ['name_en' => 'Cement Sand Plastering', 'name_am' => 'የሲሚንቶ ፕላስተር ሥራ',    'name_so' => 'Balastarka Simantada', 'category' => 'finishing',  'unit' => 'm²',  'default_unit_rate_etb' => 360],
            ['name_en' => 'Ceramic Floor Tiling',   'name_am' => 'የሴራሚክ ወለል ሥራ',      'name_so' => 'Foorada Dhoobada',     'category' => 'finishing',  'unit' => 'm²',  'default_unit_rate_etb' => 980],
            ['name_en' => 'Conduit & Wire Lighting', 'name_am' => 'የኤሌክትሪክ ዝርጋታ',       'name_so' => 'Xargaha Korontada',    'category' => 'electrical', 'unit' => 'pts', 'default_unit_rate_etb' => 1450],
            ['name_en' => 'PPR Water Supply Piping', 'name_am' => 'የውሃ ቧንቧ ዝርጋታ',       'name_so' => 'Tuubooyinka Biyaha',   'category' => 'plumbing',   'unit' => 'm',   'default_unit_rate_etb' => 520],
            ['name_en' => 'Emulsion Wall Painting', 'name_am' => 'የግድግዳ ቀለም ቅብ',       'name_so' => 'Rinjiyaynta Derbiga',  'category' => 'finishing',  'unit' => 'm²',  'default_unit_rate_etb' => 210],
        ];
        $createdWorkTypes = [];
        foreach ($workTypesData as $wt) {
            $createdWorkTypes[] = WorkType::firstOrCreate(['name_en' => $wt['name_en']], $wt);
        }

        // ── 3. Materials Inventory Catalog ─────────────────────────────────────
        $materialsData = [
            ['name_en' => 'Portland Cement (Dangote/Muger 42.5N)', 'name_am' => 'ፖርትላንድ ሲሚንቶ 42.5N', 'name_so' => 'Simintu Dangote 42.5N', 'sku' => 'MAT-CEM-01', 'unit' => 'bag',  'stock_qty' => 450, 'reorder_level' => 100, 'unit_cost_etb' => 680,  'supplier' => 'National Cement Corp'],
            ['name_en' => 'River Sand (Wondo Genet)',              'name_am' => 'የወንዝ አሸዋ (ወንዶ ገነት)', 'name_so' => 'Ciidda Wabiga',          'sku' => 'MAT-SND-01', 'unit' => 'm³',   'stock_qty' => 140, 'reorder_level' => 30,  'unit_cost_etb' => 1350, 'supplier' => 'Rift Valley Aggregates'],
            ['name_en' => 'Crushed Basalt Aggregate (02)',         'name_am' => 'የተፈጨ ባዛልት ጠጠር 02',     'name_so' => 'Dhagax Basalt',         'sku' => 'MAT-AGG-02', 'unit' => 'm³',   'stock_qty' => 110, 'reorder_level' => 25,  'unit_cost_etb' => 1550, 'supplier' => 'Addis Stone Crusher'],
            ['name_en' => 'Ribbed Steel Rebar Ø16 (Grade 60)',     'name_am' => 'ብረት Ø16 (ግሬድ 60)',       'name_so' => 'Birta Ø16',             'sku' => 'MAT-STL-16', 'unit' => 'pcs',  'stock_qty' => 15,  'reorder_level' => 40,  'unit_cost_etb' => 1250, 'supplier' => 'Akaki Steel Industry'],
            ['name_en' => 'Ribbed Steel Rebar Ø12 (Grade 60)',     'name_am' => 'ብረት Ø12 (ግሬድ 60)',       'name_so' => 'Birta Ø12',             'sku' => 'MAT-STL-12', 'unit' => 'pcs',  'stock_qty' => 18,  'reorder_level' => 50,  'unit_cost_etb' => 620,  'supplier' => 'Akaki Steel Industry'],
            ['name_en' => 'Hollow Concrete Blocks (20x20x40)',     'name_am' => 'የሲሚንቶ ብሎኬት (20x20x40)', 'name_so' => 'Blocks 20x20x40',       'sku' => 'MAT-BLK-20', 'unit' => 'pcs',  'stock_qty' => 2400,'reorder_level' => 500, 'unit_cost_etb' => 42,   'supplier' => 'Bole Block Factory'],
            ['name_en' => 'PPR Pipe Ø25mm (Hot/Cold PN20)',        'name_am' => 'PPR ቧንቧ Ø25ሚ.ሜ',         'name_so' => 'Tuubo PPR 25mm',        'sku' => 'MAT-PPR-25', 'unit' => 'm',    'stock_qty' => 180, 'reorder_level' => 60,  'unit_cost_etb' => 115,  'supplier' => 'Ethio Plastic PLC'],
            ['name_en' => 'Acrylic Emulsion Paint (Super White)',  'name_am' => 'አክሪሊክ ቀለም (ነጭ)',        'name_so' => 'Rinji Caddaan',         'sku' => 'MAT-PNT-01', 'unit' => 'can',  'stock_qty' => 45,  'reorder_level' => 15,  'unit_cost_etb' => 2850, 'supplier' => 'Kadisco Paints'],
        ];
        $createdMaterials = [];
        foreach ($materialsData as $m) {
            $createdMaterials[] = Material::firstOrCreate(['sku' => $m['sku']], $m);
        }

        // ── 4. Main Platform Admin & Default Accounts ──────────────────────────
        $globalAdmin = User::firstOrCreate(
            ['email' => 'admin@shacrawi.com'],
            ['name' => 'Sunset Admin (SaaS Owner)', 'password' => Hash::make('password'), 'locale' => 'en']
        );
        $globalAdmin->assignRole($saasAdminRole);
        $globalAdmin->assignRole($adminRole);

        $defaultForeman = User::firstOrCreate(
            ['email' => 'foreman@shacrawi.com'],
            ['name' => 'Abebe Kebede (Lead Foreman)', 'password' => Hash::make('password'), 'locale' => 'am', 'phone' => '+251911223344']
        );
        $defaultForeman->assignRole($foremanRole);
        Foreman::firstOrCreate(['user_id' => $defaultForeman->id], ['specialty' => 'Civil & Structural', 'phone' => '+251911223344']);

        // ── 5. Seed 3 Demo Companies & Their 3 Users ───────────────────────────

        $companies = [
            [
                'name'    => 'Abyssinia Builders Group',
                'tenant'  => 'abyssinia',
                'domain'  => 'abyssinia.sunset.local',
                'admin'   => ['name' => 'Dawit Tadesse (MD)',     'email' => 'admin@abyssinia.et',     'locale' => 'en'],
                'foreman' => ['name' => 'Bekele Megersa (Site Eng)','email' => 'foreman@abyssinia.et', 'locale' => 'am', 'specialty' => 'Structural & Foundation'],
                'worker'  => ['name' => 'Alazar Getachew (Crew Lead)','email' => 'worker@abyssinia.et','locale' => 'am'],
                'project' => [
                    'name'           => 'Addis Sky Tower Plaza (Bole Medhanialem)',
                    'code'           => 'PRJ-0101',
                    'location'       => 'Bole Sub-City, Addis Ababa',
                    'client_name'    => 'Midroc Investment Holdings',
                    'client_phone'   => '+251 11 551 8800',
                    'client_email'   => 'projects@midroc-et.com',
                    'contract_value' => 48500000.00,
                    'start_date'     => now()->subMonths(4)->toDateString(),
                    'end_date'       => now()->addMonths(14)->toDateString(),
                    'status'         => 'active',
                    'description'    => '14-storey mixed-use commercial and luxury office tower with 2-level basement parking.',
                ],
                'estimate_title' => 'Main Structural & Basement BOQ (Phase 1)',
                'estimate_items' => [
                    ['desc' => 'Bulk excavation in volcanic basalt rock', 'unit' => 'm³', 'qty' => 4500, 'rate' => 310,  'wt_idx' => 0],
                    ['desc' => 'Raft foundation concrete C-30 cast in-situ','unit' => 'm³', 'qty' => 1200, 'rate' => 9800, 'wt_idx' => 1],
                    ['desc' => 'High-yield deformed steel rebar Ø20/Ø16', 'unit' => 'pcs','qty' => 3200, 'rate' => 1450, 'wt_idx' => 1],
                    ['desc' => 'External masonry perimeter walls (20cm)',  'unit' => 'm²', 'qty' => 2800, 'rate' => 880,  'wt_idx' => 2],
                    ['desc' => 'Basement water pump and drainage MEP',     'unit' => 'pts','qty' => 45,   'rate' => 6500, 'wt_idx' => 6],
                ],
                'budgets' => [
                    ['cat' => 'Labour',     'alloc' => 9500000,  'spent' => 3820000],
                    ['cat' => 'Materials',  'alloc' => 24000000, 'spent' => 11450000],
                    ['cat' => 'Equipment',  'alloc' => 6500000,  'spent' => 2950000],
                    ['cat' => 'Overhead',   'alloc' => 3500000,  'spent' => 1200000],
                    ['cat' => 'Contingency','alloc' => 5000000,  'spent' => 650000],
                ],
                'timelines' => [
                    ['phase' => 'Basement Excavation & Shoring', 'phase_am' => 'የመሬት ቁፋሮ እና ድጋፍ', 'planned_start' => now()->subMonths(4)->toDateString(), 'planned_end' => now()->subMonths(2)->toDateString(), 'prog' => 100, 'status' => 'completed'],
                    ['phase' => 'Raft Foundation & Retaining Wall', 'phase_am' => 'የመሠረት እና የኮንክሪት ሥራ', 'planned_start' => now()->subMonths(2)->toDateString(), 'planned_end' => now()->toDateString(), 'prog' => 100, 'status' => 'completed'],
                    ['phase' => 'Tower Frame (Levels 1 to 7)', 'phase_am' => 'የህንፃው ፍሬም (ፎቅ 1-7)', 'planned_start' => now()->toDateString(), 'planned_end' => now()->addMonths(6)->toDateString(), 'prog' => 45, 'status' => 'in_progress'],
                    ['phase' => 'MEP & Facade Glazing', 'phase_am' => 'የኤሌክትሪክ እና የውጪ ግድግዳ ሥራ', 'planned_start' => now()->addMonths(5)->toDateString(), 'planned_end' => now()->addMonths(12)->toDateString(), 'prog' => 0, 'status' => 'not_started'],
                ],
            ],
            [
                'name'    => 'Horn of Africa Construction Ltd',
                'tenant'  => 'horn-construction',
                'domain'  => 'horn.sunset.local',
                'admin'   => ['name' => 'Guled Hassan (Executive Dir)','email' => 'admin@hornconstruction.so', 'locale' => 'so'],
                'foreman' => ['name' => 'Ahmed Jama (Site Foreman)',   'email' => 'foreman@hornconstruction.so','locale' => 'so', 'specialty' => 'Commercial & Infrastructure'],
                'worker'  => ['name' => 'Omar Faruk (Lead Mason)',      'email' => 'worker@hornconstruction.so', 'locale' => 'so'],
                'project' => [
                    'name'           => 'Jigjiga Logistics Hub & Mall',
                    'code'           => 'PRJ-0102',
                    'location'       => 'Jigjiga Central Market, Somali Region',
                    'client_name'    => 'Horn Trade & Development Agency',
                    'client_phone'   => '+251 25 775 3210',
                    'client_email'   => 'procurement@horntrade.org',
                    'contract_value' => 32000000.00,
                    'start_date'     => now()->subMonths(2)->toDateString(),
                    'end_date'       => now()->addMonths(10)->toDateString(),
                    'status'         => 'active',
                    'description'    => '4-storey commercial center with modern cold storage and warehouse distribution terminal.',
                ],
                'estimate_title' => 'Warehouse Structure & Retail Shell BOQ',
                'estimate_items' => [
                    ['desc' => 'Site leveling & compacted sub-base road', 'unit' => 'm²', 'qty' => 6000, 'rate' => 220,  'wt_idx' => 0],
                    ['desc' => 'Reinforced grade beams and pedestal columns','unit' => 'm³','qty' => 850,  'rate' => 8900, 'wt_idx' => 1],
                    ['desc' => 'Heavy duty HCB block masonry walls',      'unit' => 'm²', 'qty' => 3400, 'rate' => 820,  'wt_idx' => 2],
                    ['desc' => 'Industrial polished concrete flooring',   'unit' => 'm²', 'qty' => 2200, 'rate' => 650,  'wt_idx' => 4],
                    ['desc' => 'High-bay solar LED illumination system',  'unit' => 'pts','qty' => 60,   'rate' => 3800, 'wt_idx' => 5],
                ],
                'budgets' => [
                    ['cat' => 'Labour',     'alloc' => 6800000,  'spent' => 2200000],
                    ['cat' => 'Materials',  'alloc' => 16500000, 'spent' => 7800000],
                    ['cat' => 'Equipment',  'alloc' => 4500000,  'spent' => 1850000],
                    ['cat' => 'Overhead',   'alloc' => 2200000,  'spent' => 900000],
                    ['cat' => 'Contingency','alloc' => 2000000,  'spent' => 250000],
                ],
                'timelines' => [
                    ['phase' => 'Site Earthworks & Perimeter Wall', 'phase_am' => 'የመሬት ዝግጅት እና አጥር', 'planned_start' => now()->subMonths(2)->toDateString(), 'planned_end' => now()->subMonth()->toDateString(), 'prog' => 100, 'status' => 'completed'],
                    ['phase' => 'Foundation & Steel Framing', 'phase_am' => 'የመሠረት እና የብረት ሥራ', 'planned_start' => now()->subMonth()->toDateString(), 'planned_end' => now()->addMonths(3)->toDateString(), 'prog' => 60, 'status' => 'in_progress'],
                    ['phase' => 'Roofing & Cold Storage Facility', 'phase_am' => 'የጣሪያ እና የማቀዝቀዣ ክፍል', 'planned_start' => now()->addMonths(3)->toDateString(), 'planned_end' => now()->addMonths(7)->toDateString(), 'prog' => 0, 'status' => 'not_started'],
                ],
            ],
            [
                'name'    => 'Sunset Premier Engineering PLC',
                'tenant'  => 'sunset-premier',
                'domain'  => 'premier.sunset.local',
                'admin'   => ['name' => 'Martha Haile (Managing Partner)','email' => 'admin@sunsetpremier.com', 'locale' => 'en'],
                'foreman' => ['name' => 'Yared Belay (Lead Project Eng)', 'email' => 'foreman@sunsetpremier.com','locale' => 'am', 'specialty' => 'Luxury Residential & Finishing'],
                'worker'  => ['name' => 'Samson Alemayehu (Finishing Tech)','email' => 'worker@sunsetpremier.com','locale' => 'am'],
                'project' => [
                    'name'           => 'Hawassa Lakeview Luxury Eco-Villas',
                    'code'           => 'PRJ-0103',
                    'location'       => 'Hawassa Lakefront Boulevard, Sidama',
                    'client_name'    => 'Rift Hospitality & Resorts Ltd',
                    'client_phone'   => '+251 46 220 9540',
                    'client_email'   => 'developments@rifthospitality.com',
                    'contract_value' => 64000000.00,
                    'start_date'     => now()->subMonth()->toDateString(),
                    'end_date'       => now()->addMonths(16)->toDateString(),
                    'status'         => 'active',
                    'description'    => 'Cluster of 8 luxury sustainable vacation villas with infinity pools and private solar micro-grids.',
                ],
                'estimate_title' => 'Villa Cluster A & B Turnkey BOQ',
                'estimate_items' => [
                    ['desc' => 'Ecological foundation & pile support',    'unit' => 'm³', 'qty' => 650,  'rate' => 10500,'wt_idx' => 1],
                    ['desc' => 'Natural stone & exposed brick masonry',   'unit' => 'm²', 'qty' => 1800, 'rate' => 1400, 'wt_idx' => 2],
                    ['desc' => 'Imported Italian porcelain floor tiling', 'unit' => 'm²', 'qty' => 1400, 'rate' => 2400, 'wt_idx' => 4],
                    ['desc' => 'Solar thermal hydronic underfloor heating','unit' => 'pts','qty' => 24,   'rate' => 12500,'wt_idx' => 6],
                    ['desc' => 'Premium textured weather-shield paint',   'unit' => 'm²', 'qty' => 3200, 'rate' => 420,  'wt_idx' => 7],
                ],
                'budgets' => [
                    ['cat' => 'Labour',     'alloc' => 12000000, 'spent' => 2800000],
                    ['cat' => 'Materials',  'alloc' => 32000000, 'spent' => 9200000],
                    ['cat' => 'Equipment',  'alloc' => 8500000,  'spent' => 2100000],
                    ['cat' => 'Overhead',   'alloc' => 5500000,  'spent' => 1400000],
                    ['cat' => 'Contingency','alloc' => 6000000,  'spent' => 450000],
                ],
                'timelines' => [
                    ['phase' => 'Lakefront Earth Stabilization', 'phase_am' => 'የመሬት ማረጋጋት ሥራ', 'planned_start' => now()->subMonth()->toDateString(), 'planned_end' => now()->toDateString(), 'prog' => 100, 'status' => 'completed'],
                    ['phase' => 'Villa Foundations & Pools', 'phase_am' => 'የቪላ መሠረት እና የመዋኛ ገንዳ', 'planned_start' => now()->toDateString(), 'planned_end' => now()->addMonths(5)->toDateString(), 'prog' => 30, 'status' => 'in_progress'],
                    ['phase' => 'Timber Roofing & Solar Envelope', 'phase_am' => 'የእንጨት ጣሪያ እና የሶላር ሥራ', 'planned_start' => now()->addMonths(4)->toDateString(), 'planned_end' => now()->addMonths(10)->toDateString(), 'prog' => 0, 'status' => 'not_started'],
                    ['phase' => 'Interior Luxury Finishing', 'phase_am' => 'የውስጥ የቅንጦት ማጠናቀቂያ', 'planned_start' => now()->addMonths(9)->toDateString(), 'planned_end' => now()->addMonths(15)->toDateString(), 'prog' => 0, 'status' => 'not_started'],
                ],
            ],
        ];

        foreach ($companies as $comp) {
            // Seed Central Tenant
            DB::table('tenants')->updateOrInsert(
                ['id' => $comp['tenant']],
                ['name' => $comp['name'], 'plan' => 'pro', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()]
            );
            DB::table('domains')->updateOrInsert(
                ['domain' => $comp['domain']],
                ['tenant_id' => $comp['tenant'], 'created_at' => now(), 'updated_at' => now()]
            );

            // 1. Company / Tenant Admin User
            $adminUser = User::firstOrCreate(
                ['email' => $comp['admin']['email']],
                ['name' => $comp['admin']['name'], 'password' => Hash::make('password'), 'locale' => $comp['admin']['locale']]
            );
            $adminUser->assignRole($tenantAdminRole);
            $adminUser->assignRole($adminRole);

            // 2. Foreman User
            $foremanUser = User::firstOrCreate(
                ['email' => $comp['foreman']['email']],
                ['name' => $comp['foreman']['name'], 'password' => Hash::make('password'), 'locale' => $comp['foreman']['locale'], 'phone' => '+2519' . rand(10000000, 99999999)]
            );
            $foremanUser->assignRole($foremanRole);
            $foremanProfile = Foreman::firstOrCreate(
                ['user_id' => $foremanUser->id],
                ['specialty' => $comp['foreman']['specialty'], 'phone' => $foremanUser->phone]
            );

            // 3. Worker User
            $workerUser = User::firstOrCreate(
                ['email' => $comp['worker']['email']],
                ['name' => $comp['worker']['name'], 'password' => Hash::make('password'), 'locale' => $comp['worker']['locale']]
            );
            $workerUser->assignRole($workerRole);

            // 4. Project
            $prjData = $comp['project'];
            $prjData['created_by'] = $adminUser->id;
            $prjData['assigned_foreman_id'] = $foremanUser->id;
            $project = Project::firstOrCreate(['name' => $prjData['name']], $prjData);
            $foremanProfile->update(['current_project_id' => $project->id]);

            // 5. Estimation & Items
            $est = Estimation::firstOrCreate(
                ['project_id' => $project->id, 'title' => $comp['estimate_title']],
                [
                    'status'          => 'approved',
                    'contingency_pct' => 7.5,
                    'created_by'      => $adminUser->id,
                    'approved_by'     => $adminUser->id,
                    'approved_at'     => now()->subMonths(2),
                ]
            );

            foreach ($comp['estimate_items'] as $idx => $ei) {
                $wt = $createdWorkTypes[$ei['wt_idx']] ?? $createdWorkTypes[0];
                EstimationItem::firstOrCreate(
                    ['estimation_id' => $est->id, 'description' => $ei['desc']],
                    [
                        'work_type_id'   => $wt->id,
                        'unit'           => $ei['unit'],
                        'qty'            => $ei['qty'],
                        'unit_price_etb' => $ei['rate'],
                        'total_etb'      => round($ei['qty'] * $ei['rate'], 2),
                        'sort_order'     => $idx,
                    ]
                );
            }
            $est->recalculate();

            // 6. Budgets & Transactions
            foreach ($comp['budgets'] as $b) {
                $budget = Budget::firstOrCreate(
                    ['project_id' => $project->id, 'category' => $b['cat']],
                    ['allocated_etb' => $b['alloc'], 'spent_etb' => $b['spent']]
                );

                BudgetTransaction::firstOrCreate(
                    ['budget_id' => $budget->id, 'reference' => 'TX-' . $project->id . '-' . substr($b['cat'], 0, 3)],
                    [
                        'amount_etb'    => round($b['spent'] * 0.6, 2),
                        'type'          => 'debit',
                        'note'          => "Initial advance spend allocation for {$b['cat']}",
                        'recorded_by'   => $adminUser->id,
                        'transacted_at' => now()->subMonth(),
                    ]
                );
            }

            // 7. Timelines
            foreach ($comp['timelines'] as $tIdx => $tl) {
                Timeline::firstOrCreate(
                    ['project_id' => $project->id, 'phase_name' => $tl['phase']],
                    [
                        'phase_name_am' => $tl['phase_am'],
                        'planned_start' => $tl['planned_start'],
                        'planned_end'   => $tl['planned_end'],
                        'progress_pct'  => $tl['prog'],
                        'status'        => $tl['status'],
                        'sort_order'    => $tIdx,
                    ]
                );
            }

            // 8. Material Requests
            $matReq = MaterialRequest::firstOrCreate(
                ['project_id' => $project->id, 'foreman_id' => $foremanUser->id],
                [
                    'status'      => 'approved',
                    'reason'      => 'Urgent site delivery for ongoing concrete foundation casting',
                    'reviewed_by' => $adminUser->id,
                    'reviewed_at' => now()->subDays(3),
                ]
            );
            MaterialRequestItem::firstOrCreate(
                ['material_request_id' => $matReq->id, 'material_id' => $createdMaterials[0]->id],
                ['qty_requested' => 120, 'qty_approved' => 120, 'unit_cost_etb' => $createdMaterials[0]->unit_cost_etb]
            );
            MaterialRequestItem::firstOrCreate(
                ['material_request_id' => $matReq->id, 'material_id' => $createdMaterials[1]->id],
                ['qty_requested' => 35,  'qty_approved' => 35,  'unit_cost_etb' => $createdMaterials[1]->unit_cost_etb]
            );

            // 9. Labor Logs
            $dates = [now()->subDays(4), now()->subDays(3), now()->subDays(2), now()->subDay(), now()];
            $workers = [
                ['name' => 'Mulugeta Assefa', 'rate' => 650, 'hours' => 8],
                ['name' => 'Kassahun Desta',  'rate' => 550, 'hours' => 8],
                ['name' => 'Girma Tefera',    'rate' => 700, 'hours' => 8.5],
                ['name' => 'Tewodros Bekele', 'rate' => 600, 'hours' => 8],
            ];
            foreach ($dates as $d) {
                foreach ($workers as $w) {
                    LaborLog::firstOrCreate(
                        ['project_id' => $project->id, 'worker_name' => $w['name'], 'log_date' => $d->toDateString()],
                        [
                            'foreman_id'     => $foremanUser->id,
                            'work_type_id'   => $createdWorkTypes[1]->id,
                            'hours_worked'   => $w['hours'],
                            'daily_rate_etb' => $w['rate'],
                            'total_cost_etb' => round(($w['hours'] / 8) * $w['rate'], 2),
                            'notes'          => 'Cast-in-place concrete foundation reinforcement',
                        ]
                    );
                }
            }
        }

        $this->command->info('✅ Successfully Seeded: 3 Demo Construction Companies with full Estimations, BOQs, Budgets, Timelines, Material Requests & Labor Logs!');
    }
}
