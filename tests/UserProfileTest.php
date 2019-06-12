<?php

namespace XADMIN\LaravelCmf\Tests;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use XADMIN\LaravelCmf\Models\Role;
use XADMIN\LaravelCmf\Models\User;

class UserProfileTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;

    protected $editPageForTheCurrentUser;

    protected $listOfUsers;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = Auth::loginUsingId(1);

        $this->editPageForTheCurrentUser = route('laravel-cmf.users.edit', ['user' => $this->user->id]);

        $this->listOfUsers = route('laravel-cmf.users.index');

        $this->withFactories(__DIR__.'/database/factories');
    }

    public function testCanSeeTheUserInfoOnHisProfilePage()
    {
        $this->visit(route('laravel-cmf.profile'))
             ->seeInElement('h4', $this->user->name)
             ->seeInElement('.user-email', $this->user->email)
             ->seeLink(__('laravel-cmf::profile.edit'));
    }

    public function testCanEditUserName()
    {
        $this->visit(route('laravel-cmf.profile'))
             ->click(__('laravel-cmf::profile.edit'))
             ->see(__('laravel-cmf::profile.edit_user'))
             ->seePageIs($this->editPageForTheCurrentUser)
             ->type('New Awesome Name', 'name')
             ->press(__('laravel-cmf::generic.save'))
             ->seePageIs($this->listOfUsers)
             ->seeInDatabase(
                 'users',
                 ['name' => 'New Awesome Name']
             );
    }

    public function testCanEditUserEmail()
    {
        $this->visit(route('laravel-cmf.profile'))
             ->click(__('laravel-cmf::profile.edit'))
             ->see(__('laravel-cmf::profile.edit_user'))
             ->seePageIs($this->editPageForTheCurrentUser)
             ->type('another@email.com', 'email')
             ->press(__('laravel-cmf::generic.save'))
             ->seePageIs($this->listOfUsers)
             ->seeInDatabase(
                 'users',
                 ['email' => 'another@email.com']
             );
    }

    public function testCanEditUserPassword()
    {
        $this->visit(route('laravel-cmf.profile'))
             ->click(__('laravel-cmf::profile.edit'))
             ->see(__('laravel-cmf::profile.edit_user'))
             ->seePageIs($this->editPageForTheCurrentUser)
             ->type('laravel-cmf-rocks', 'password')
             ->press(__('laravel-cmf::generic.save'))
             ->seePageIs($this->listOfUsers);

        $updatedPassword = DB::table('users')->where('id', 1)->first()->password;
        $this->assertTrue(Hash::check('laravel-cmf-rocks', $updatedPassword));
    }

    public function testCanEditUserAvatar()
    {
        $this->visit(route('laravel-cmf.profile'))
             ->click(__('laravel-cmf::profile.edit'))
             ->see(__('laravel-cmf::profile.edit_user'))
             ->seePageIs($this->editPageForTheCurrentUser)
             ->attach($this->newImagePath(), 'avatar')
             ->press(__('laravel-cmf::generic.save'))
             ->seePageIs($this->listOfUsers)
             ->dontSeeInDatabase(
                 'users',
                 ['id' => 1, 'avatar' => 'user/default.png']
             );
    }

    public function testCanEditUserEmailWithEditorPermissions()
    {
        $user = factory(\XADMIN\LaravelCmf\Models\User::class)->create();
        $editPageForTheCurrentUser = route('laravel-cmf.users.edit', ['user' => $user->id]);
        $roleId = $user->role_id;
        $role = Role::find($roleId);
        // add permissions which reflect a possible editor role
        // without permissions to edit  users
        $role->permissions()->attach(\XADMIN\LaravelCmf\Models\Permission::whereIn('key', [
            'browse_admin',
            'browse_users',
        ])->get()->pluck('id')->all());
        Auth::onceUsingId($user->id);
        $this->visit(route('laravel-cmf.profile'))
             ->click(__('laravel-cmf::profile.edit'))
             ->see(__('laravel-cmf::profile.edit_user'))
             ->seePageIs($editPageForTheCurrentUser)
             ->type('another@email.com', 'email')
             ->press(__('laravel-cmf::generic.save'))
             ->seePageIs($this->listOfUsers)
             ->seeInDatabase(
                 'users',
                 ['email' => 'another@email.com']
             );
    }

    public function testCanSetUserLocale()
    {
        $this->visit(route('laravel-cmf.profile'))
             ->click(__('laravel-cmf::profile.edit'))
             ->see(__('laravel-cmf::profile.edit_user'))
             ->seePageIs($this->editPageForTheCurrentUser)
             ->select('de', 'locale')
             ->press(__('laravel-cmf::generic.save'));

        $user = User::find(1);
        $this->assertTrue(($user->locale == 'de'));

        // Validate that app()->setLocale() is called
        Auth::loginUsingId($user->id);
        $this->visitRoute('laravel-cmf.dashboard');
        $this->assertTrue(($user->locale == $this->app->getLocale()));
    }

    protected function newImagePath()
    {
        return realpath(__DIR__.'/temp/new_avatar.png');
    }
}
