
namespace {{ $namespace }};

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App;

class {{ $testName }} extends TestCase
{
    use DatabaseTransactions;

    public function test_index()
    {
@if ($protected)
        $user = factory(App\User::class)->create();
        $user->attachPermission('{{ $readPermission }}');

        $url = '{{ $url }}';

        $this->actingAs($user, 'web')
            ->get($url)
            ->assertStatus(200);
@else
        $url = '{{ $url }}';

        $this->get($url)
            ->assertStatus(200);
@endif
    }

    public function test_create()
    {
@if ($protected)
        $user = factory(App\User::class)->create();
        $user->attachPermission('{{ $createPermission }}');

        $url = '{{ $url }}/create';

        $this->actingAs($user)
            ->get($url)
            ->assertStatus(200);
@else
        $url = '{{ $url }}/create';

        $this->actingAs($user)
            ->get($url)
            ->assertStatus(200);
@endif
    }

    public function test_store()
    {
@if ($protected)
        $user = factory(App\User::class)->create();
        $user->attachPermission('{{ $createPermission }}');

        $url = '{{ $url }}';
        $params = [
            // Fill here your payload!
        ];

        $this->actingAs($user, 'web')
            ->post($url, $params)
            ->assertStatus(302);

        $this->assertDatabaseHas('{{ $tableName }}', $params);
@else
        $url = '{{ $url }}';
        $params = [
            // Fill here your payload!
        ];

        $this->post($url, $params)
            ->assertStatus(302);

        $this->assertDatabaseHas('{{ $tableName }}', $params);
@endif
    }

    public function test_edit()
    {
@if ($protected)
        $user = factory(App\User::class)->create();
        $user->attachPermission('{{ $updatePermission }}');
        {{ $object }} = factory({{ $modelClass }}::class)->create();

        $url = sprintf('{{ $url }}/%d/edit', {{ $object }}->id);

        $this->actingAs($user, 'web')
            ->get($url)
            ->assertStatus(200);
@else
        {{ $object }} = factory({{ $modelClass }}::class)->create();

        $url = sprintf('{{ $url }}/%d/edit', {{ $object }}->id);

        $this->get($url)
            ->assertStatus(200);
@endif
    }

    public function test_update()
    {
@if ($protected)
        $user = factory(App\User::class)->create();
        $user->attachPermission('{{ $updatePermission }}');
        {{ $object }} = factory({{ $modelClass }}::class)->create();

        $url = sprintf('{{ $url }}/%d', {{ $object }}->id);
        $params = [
            // Fill here your payload!
        ];

        $this->actingAs($user, 'web')
            ->patch($url, $params)
            ->assertStatus(302);

        $this->assertDatabaseHas('{{ $items }}', $params);
@else
        $url = sprintf('{{ $url }}/%d', {{ $object }}->id);
        $params = [
            // Fill here your payload!
        ];

        $this->patch($url, $params)
            ->assertStatus(302);

        $this->assertDatabaseHas('{{ $items }}', $params);
@endif
    }

    public function test_delete()
    {
@if ($protected)
        $user = factory(App\User::class)->create();
        $user->attachPermission('{{ $deletePermission }}');
        {{ $object }} = factory({{ $modelClass }}::class)->create();

        $url = sprintf('{{ $url }}/%d', {{ $object }}->id);

        $this->actingAs($user, 'web')
            ->delete($url)
            ->assertStatus(302);
@if ($useSoftDeletes)
        $this->assertSoftDeleted('{{ $items }}', ['id' => {{ $object }}->id]);
@else
        $this->assertDatabaseMissing('{{ $items }}', ['id' => {{ $object }}->id]);
@endif
@else
        {{ $object }} = factory({{ $modelClass }}::class)->create();

        $url = sprintf('{{ $url }}/%d', {{ $object }}->id);

        $this->delete($url)
            ->assertStatus(302);

@if ($useSoftDeletes)
        $this->assertSoftDeleted('{{ $items }}', ['id' => {{ $object }}->id]);
@else
        $this->assertDatabaseMissing('{{ $items }}', ['id' => {{ $object }}->id]);
@endif
@endif
    }

}
