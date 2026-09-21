<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $administrator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->administrator = $this->uzytkownikZRola('administrator');
    }

    /**
     * Tworzy użytkownika z podaną rolą.
     */
    protected function uzytkownikZRola(string $nazwaRoli): User
    {
        $rola = Role::firstOrCreate(['name' => $nazwaRoli]);
        $uzytkownik = User::factory()->create();
        $uzytkownik->roles()->attach($rola);

        return $uzytkownik;
    }

    public function test_administrator_widzi_liste_uzytkownikow(): void
    {
        $this->actingAs($this->administrator)
            ->get(route('admin.users.index'))
            ->assertStatus(200)
            ->assertSee('Zarządzanie użytkownikami');
    }

    public function test_administrator_widzi_formularz_dodawania(): void
    {
        $this->actingAs($this->administrator)
            ->get(route('admin.users.create'))
            ->assertStatus(200)
            ->assertSee('Dodaj użytkownika');
    }

    public function test_administrator_widzi_formularz_edycji(): void
    {
        $edytowany = User::factory()->create();

        $this->actingAs($this->administrator)
            ->get(route('admin.users.edit', $edytowany->id))
            ->assertStatus(200)
            ->assertSee('Edytuj użytkownika');
    }

    public function test_pracownik_nie_ma_dostepu_do_zarzadzania_uzytkownikami(): void
    {
        $pracownik = $this->uzytkownikZRola('pracownik');

        $this->actingAs($pracownik)
            ->get(route('admin.users.index'))
            ->assertRedirect('/dashboard')
            ->assertSessionHas('error', 'Forbidden');
    }

    public function test_kierownik_nie_ma_dostepu_do_zarzadzania_uzytkownikami(): void
    {
        $kierownik = $this->uzytkownikZRola('kierownik');

        $this->actingAs($kierownik)
            ->get(route('admin.users.index'))
            ->assertRedirect('/manager/dashboard')
            ->assertSessionHas('error', 'Forbidden');
    }

    public function test_pracownik_nie_ma_dostepu_do_panelu_kierownika(): void
    {
        $pracownik = $this->uzytkownikZRola('pracownik');

        $this->actingAs($pracownik)
            ->get(route('manager.dashboard'))
            ->assertRedirect('/dashboard')
            ->assertSessionHas('error', 'Forbidden');
    }

    public function test_niezalogowany_jest_przekierowany_na_logowanie(): void
    {
        $this->get(route('admin.users.index'))
            ->assertRedirect('/login');
    }
}
