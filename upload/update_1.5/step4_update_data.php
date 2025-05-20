use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Add 'expiry_period' column to 'courses' table
if (!Schema::hasColumn('courses', 'expiry_period')) {
    Schema::table('courses', function (Blueprint $table) {
        $table->integer('expiry_period')->nullable(); // Add expiry_period as nullable integer
    });
}

// Update double column types where necessary with empty length/values
$doubleTablesForDecimal = [
    'bootcamps' => ['price', 'discounted_price'],
    'bootcamp_purchases' => ['price', 'tax', 'admin_revenue', 'instructor_revenue'],
    'courses' => ['price', 'discounted_price'],
];

foreach ($doubleTablesForDecimal as $table => $columns) {
    foreach ($columns as $column) {
        if (Schema::hasColumn($table, $column)) {
            Schema::table($table, function (Blueprint $table) use ($column) {
                $table->double($column)->nullable()->change(); // Change column type to double without length/values
            });
        }
    }
}

// Update float to double column types where necessary with empty length/values
$doubleTablesForFloat = [
    'coupons' => ['discount'],
    'team_package_purchases' => ['price', 'admin_revenue', 'instructor_revenue', 'tax'],
    'tutor_bookings' => ['price', 'admin_revenue', 'instructor_revenue', 'tax'],
];

foreach ($doubleTablesForFloat as $table => $columns) {
    foreach ($columns as $column) {
        if (Schema::hasColumn($table, $column)) {
            Schema::table($table, function (Blueprint $table) use ($column) {
                $table->double($column)->nullable()->change(); // Change float to double without length/values
            });
        }
    }
}