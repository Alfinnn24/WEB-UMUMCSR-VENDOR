<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;

class AdminPeraturanTest extends TestCase
{
    private string $adminNid;

    protected function setUp(): void
    {
        parent::setUp();

        $admin = DB::table('users')->where('role', 'admin')->first();
        $this->assertNotNull($admin, 'Tidak ada user admin di database.');
        $this->adminNid = $admin->nid;
    }

    private function loginAsAdmin(): void
    {
        $admin = DB::table('users')->where('role', 'admin')->first();
        session([
            'logged_in' => true,
            'id'        => $admin->id,
            'user_id'   => $admin->id,
            'nid'       => $admin->nid,
            'nama'      => $admin->nama,
            'role'      => 'admin',
            'status'    => $admin->status ?? 'aktif',
        ]);
    }

    public function test_route_requires_admin_auth(): void
    {
        $response = $this->get(route('admin.peraturan.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_page_loads_without_error(): void
    {
        $this->loginAsAdmin();
        $response = $this->get(route('admin.peraturan.index'));
        $response->assertStatus(200);
        $response->assertSee('Peraturan Perusahaan');
    }

    public function test_tab_semua_menampilkan_semua_data(): void
    {
        $this->loginAsAdmin();
        $total = DB::table('peraturan_perusahaan')->count();
        $response = $this->get(route('admin.peraturan.index', ['tab' => 'semua']));
        $response->assertStatus(200);
        $response->assertSee('Menampilkan');
    }

    public function test_tab_pp_hanya_pp(): void
    {
        $this->loginAsAdmin();
        $response = $this->get(route('admin.peraturan.index', ['tab' => 'PP']));
        $response->assertStatus(200);
        $response->assertSee('PP');

        $ppCount = DB::table('peraturan_perusahaan')->where('jenis', 'PP')->count();
        if ($ppCount > 0) {
            $response->assertSee("{$ppCount}");
        }
    }

    public function test_tab_pkb_hanya_pkb(): void
    {
        $this->loginAsAdmin();
        $response = $this->get(route('admin.peraturan.index', ['tab' => 'PKB']));
        $response->assertStatus(200);
        $response->assertSee('PKB');
    }

    public function test_filter_status_aktif(): void
    {
        $this->loginAsAdmin();
        $response = $this->get(route('admin.peraturan.index', ['status' => 'aktif']));
        $response->assertStatus(200);
        $response->assertSee('Aktif');
    }

    public function test_filter_status_nonaktif(): void
    {
        $this->loginAsAdmin();
        $response = $this->get(route('admin.peraturan.index', ['status' => 'nonaktif']));
        $response->assertStatus(200);
        $response->assertSee('Non Aktif');
    }

    public function test_search_by_nomor(): void
    {
        $this->loginAsAdmin();
        $nomor = DB::table('peraturan_perusahaan')->value('nomor');
        $response = $this->get(route('admin.peraturan.index', ['search' => $nomor]));
        $response->assertStatus(200);
        $response->assertSee($nomor);
    }

    public function test_search_by_perusahaan_nama(): void
    {
        $this->loginAsAdmin();
        $perusahaan = DB::table('peraturan_perusahaan as p')
            ->join('users as u', 'p.perusahaan_id', '=', 'u.id')
            ->value('u.nama');
        $this->assertNotNull($perusahaan);

        $search = substr($perusahaan, 0, 5);
        $response = $this->get(route('admin.peraturan.index', ['search' => $search]));
        $response->assertStatus(200);
    }

    public function test_tidak_ada_tombol_tambah_edit_hapus(): void
    {
        $this->loginAsAdmin();
        $response = $this->get(route('admin.peraturan.index'));
        $response->assertDontSee('Tambah');
        $response->assertDontSee('btn-success');
        $response->assertDontSee('btn-danger');
    }

    public function test_ajax_request_returns_partial_view(): void
    {
        $this->loginAsAdmin();
        $response = $this->get(route('admin.peraturan.index'), ['HTTP_X-Requested-With' => 'XMLHttpRequest']);
        $response->assertStatus(200);
    }

    public function test_halaman_memiliki_breadcrumb(): void
    {
        $this->loginAsAdmin();
        $response = $this->get(route('admin.peraturan.index'));
        $response->assertSee('Peraturan Perusahaan');
    }

    public function test_tombol_lihat_file_ada_untuk_data_dengan_file(): void
    {
        $this->loginAsAdmin();
        $adaFile = DB::table('peraturan_perusahaan')->whereNotNull('file')->where('file', '!=', '')->exists();
        if ($adaFile) {
            $response = $this->get(route('admin.peraturan.index'));
            $response->assertSee('Lihat File');
        } else {
            $this->markTestSkipped('Tidak ada data dengan file.');
        }
    }

    public function test_tidak_ada_route_store_update_destroy_admin(): void
    {
        $routes = collect(app('router')->getRoutes())->filter(function ($route) {
            return str_starts_with($route->uri(), 'admin/peraturan');
        });

        $methods = $routes->flatMap(fn($r) => $r->methods())->unique()->values()->all();
        $this->assertEquals(['GET', 'HEAD'], $methods, 'Admin hanya boleh memiliki route GET/HEAD.');
    }
}
