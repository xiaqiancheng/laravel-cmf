<?php

namespace XADMIN\LaravelCmf\Tests;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use XADMIN\LaravelCmf\Facades\LaravelCmf;
use XADMIN\LaravelCmf\Models\Category;
use XADMIN\LaravelCmf\Models\DataType;
use XADMIN\LaravelCmf\Models\Permission;

class FormfieldsTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Auth::loginUsingId(1);
    }

    public function testFormfieldText()
    {
        $this->createBreadForFormfield('text', 'text', json_encode([
            'default' => 'Default Text',
            'null'    => 'NULL',
        ]));

        $this->visitRoute('laravel-cmf.categories.create')
        ->see('Default Text')
        ->type('New Text', 'text')
        ->press(__('laravel-cmf::generic.save'))
        ->seeRouteIs('laravel-cmf.categories.index')
        ->see('New Text')
        ->click(__('laravel-cmf::generic.edit'))
        ->seeRouteIs('laravel-cmf.categories.edit', ['id' => 1])
        ->type('Edited Text', 'text')
        ->press(__('laravel-cmf::generic.save'))
        ->seeRouteIs('laravel-cmf.categories.index')
        ->see('Edited Text')
        ->click(__('laravel-cmf::generic.edit'))
        ->seeRouteIs('laravel-cmf.categories.edit', ['id' => 1])
        ->type('NULL', 'text')
        ->press(__('laravel-cmf::generic.save'))
        ->seeRouteIs('laravel-cmf.categories.index')
        ->seeInDatabase('categories', [
            'text' => null,
        ]);
    }

    public function testFormfieldTextbox()
    {
        $this->createBreadForFormfield('text', 'text_area', json_encode([
            'default' => 'Default Text',
        ]));

        $this->visitRoute('laravel-cmf.categories.create')
        ->see('Default Text')
        ->type('New Text', 'text_area')
        ->press(__('laravel-cmf::generic.save'))
        ->seeRouteIs('laravel-cmf.categories.index')
        ->see('New Text')
        ->click(__('laravel-cmf::generic.edit'))
        ->seeRouteIs('laravel-cmf.categories.edit', ['id' => 1])
        ->type('Edited Text', 'text_area')
        ->press(__('laravel-cmf::generic.save'))
        ->seeRouteIs('laravel-cmf.categories.index')
        ->see('Edited Text');
    }

    public function testFormfieldCodeeditor()
    {
        $this->createBreadForFormfield('text', 'code_editor', json_encode([
            'default' => 'Default Text',
        ]));

        $this->visitRoute('laravel-cmf.categories.create')
        ->see('Default Text')
        ->type('New Text', 'code_editor')
        ->press(__('laravel-cmf::generic.save'))
        ->seeRouteIs('laravel-cmf.categories.index')
        ->see('New Text')
        ->click(__('laravel-cmf::generic.edit'))
        ->seeRouteIs('laravel-cmf.categories.edit', ['id' => 1])
        ->type('Edited Text', 'code_editor')
        ->press(__('laravel-cmf::generic.save'))
        ->seeRouteIs('laravel-cmf.categories.index')
        ->see('Edited Text');
    }

    public function testFormfieldMarkdown()
    {
        $this->createBreadForFormfield('text', 'markdown_editor');

        $this->visitRoute('laravel-cmf.categories.create')
        ->type('# New Text', 'markdown_editor')
        ->press(__('laravel-cmf::generic.save'))
        ->seeRouteIs('laravel-cmf.categories.index')
        ->see('New Text')
        ->click(__('laravel-cmf::generic.edit'))
        ->seeRouteIs('laravel-cmf.categories.edit', ['id' => 1])
        ->type('# Edited Text', 'markdown_editor')
        ->press(__('laravel-cmf::generic.save'))
        ->seeRouteIs('laravel-cmf.categories.index')
        ->see('Edited Text');
    }

    public function testFormfieldRichtextbox()
    {
        $this->createBreadForFormfield('text', 'rich_text_box');

        $this->visitRoute('laravel-cmf.categories.create')
        ->type('New Text', 'rich_text_box')
        ->press(__('laravel-cmf::generic.save'))
        ->seeRouteIs('laravel-cmf.categories.index')
        ->see('New Text')
        ->click(__('laravel-cmf::generic.edit'))
        ->seeRouteIs('laravel-cmf.categories.edit', ['id' => 1])
        ->type('Edited Text', 'rich_text_box')
        ->press(__('laravel-cmf::generic.save'))
        ->seeRouteIs('laravel-cmf.categories.index')
        ->see('Edited Text');
    }

    public function testFormfieldHidden()
    {
        $this->createBreadForFormfield('text', 'hidden', json_encode([
            'default' => 'Default Text',
        ]));

        $this->visitRoute('laravel-cmf.categories.create')
        ->see('Default Text')
        ->type('New Text', 'hidden')
        ->press(__('laravel-cmf::generic.save'))
        ->seeRouteIs('laravel-cmf.categories.index')
        ->see('New Text')
        ->click(__('laravel-cmf::generic.edit'))
        ->seeRouteIs('laravel-cmf.categories.edit', ['id' => 1])
        ->type('Edited Text', 'hidden')
        ->press(__('laravel-cmf::generic.save'))
        ->seeRouteIs('laravel-cmf.categories.index')
        ->see('Edited Text');
    }

    public function testFormfieldPassword()
    {
        $this->createBreadForFormfield('text', 'password');

        $t = $this->visitRoute('laravel-cmf.categories.create')
        ->type('newpassword', 'password')
        ->press(__('laravel-cmf::generic.save'))
        ->seeRouteIs('laravel-cmf.categories.index');
        $this->assertTrue(Hash::check('newpassword', Category::first()->password));

        $t->click(__('laravel-cmf::generic.edit'))
        ->seeRouteIs('laravel-cmf.categories.edit', ['id' => 1])
        ->press(__('laravel-cmf::generic.save'))
        ->seeRouteIs('laravel-cmf.categories.index');
        $this->assertTrue(Hash::check('newpassword', Category::first()->password));
    }

    public function testFormfieldNumber()
    {
        $this->createBreadForFormfield('integer', 'number', json_encode([
            'default' => 1,
        ]));

        $this->visitRoute('laravel-cmf.categories.create')
        ->see('1')
        ->type('2', 'number')
        ->press(__('laravel-cmf::generic.save'))
        ->seeRouteIs('laravel-cmf.categories.index')
        ->see('2')
        ->click(__('laravel-cmf::generic.edit'))
        ->seeRouteIs('laravel-cmf.categories.edit', ['id' => 1])
        ->type('3', 'number')
        ->press(__('laravel-cmf::generic.save'))
        ->seeRouteIs('laravel-cmf.categories.index')
        ->see('3');
    }

    public function testFormfieldCheckbox()
    {
        $this->createBreadForFormfield('boolean', 'checkbox', json_encode([
            'on'  => 'Active',
            'off' => 'Inactive',
        ]));

        $this->visitRoute('laravel-cmf.categories.create')
        ->see('Inactive')
        ->check('checkbox')
        ->press(__('laravel-cmf::generic.save'))
        ->seeRouteIs('laravel-cmf.categories.index')
        ->see('Active')
        ->click(__('laravel-cmf::generic.edit'))
        ->seeRouteIs('laravel-cmf.categories.edit', ['id' => 1])
        ->uncheck('checkbox')
        ->press(__('laravel-cmf::generic.save'))
        ->seeRouteIs('laravel-cmf.categories.index')
        ->see('Inactive');
    }

    public function testFormfieldTime()
    {
        $this->createBreadForFormfield('time', 'time');

        $this->visitRoute('laravel-cmf.categories.create')
        ->type('12:50', 'time')
        ->press(__('laravel-cmf::generic.save'))
        ->seeRouteIs('laravel-cmf.categories.index')
        ->see('12:50')
        ->click(__('laravel-cmf::generic.edit'))
        ->seeRouteIs('laravel-cmf.categories.edit', ['id' => 1])
        ->type('6:25', 'time')
        ->press(__('laravel-cmf::generic.save'))
        ->seeRouteIs('laravel-cmf.categories.index')
        ->see('6:25');
    }

    public function testFormfieldDate()
    {
        $this->createBreadForFormfield('date', 'date', json_encode([
            'format' => '%Y-%m-%d',
        ]));

        $this->visitRoute('laravel-cmf.categories.create')
        ->type('2019-01-01', 'date')
        ->press(__('laravel-cmf::generic.save'))
        ->seeRouteIs('laravel-cmf.categories.index')
        ->see('2019-01-01')
        ->click(__('laravel-cmf::generic.edit'))
        ->seeRouteIs('laravel-cmf.categories.edit', ['id' => 1])
        ->type('2018-12-31', 'date')
        ->press(__('laravel-cmf::generic.save'))
        ->seeRouteIs('laravel-cmf.categories.index')
        ->see('2018-12-31');
    }

    public function testFormfieldTimestamp()
    {
        $this->createBreadForFormfield('timestamp', 'timestamp', json_encode([
            'format' => '%F %T',
        ]));

        $this->visitRoute('laravel-cmf.categories.create')
        ->type('2019-01-01 12:00:00', 'timestamp')
        ->press(__('laravel-cmf::generic.save'))
        ->seeRouteIs('laravel-cmf.categories.index')
        ->see('2019-01-01 12:00:00')
        ->click(__('laravel-cmf::generic.edit'))
        ->seeRouteIs('laravel-cmf.categories.edit', ['id' => 1])
        ->type('2018-12-31 23:59:59', 'timestamp')
        ->press(__('laravel-cmf::generic.save'))
        ->seeRouteIs('laravel-cmf.categories.index')
        ->see('2018-12-31 23:59:59')
        ->click(__('laravel-cmf::generic.edit'))
        ->seeRouteIs('laravel-cmf.categories.edit', ['id' => 1])
        ->type('', 'timestamp')
        ->press(__('laravel-cmf::generic.save'))
        ->seeRouteIs('laravel-cmf.categories.index')
        ->seeInDatabase('categories', [
            'timestamp' => null,
        ]);
    }

    public function testFormfieldColor()
    {
        $this->createBreadForFormfield('text', 'color');

        $this->visitRoute('laravel-cmf.categories.create')
        ->type('#FF0000', 'color')
        ->press(__('laravel-cmf::generic.save'))
        ->seeRouteIs('laravel-cmf.categories.index')
        ->see('#FF0000')
        ->click(__('laravel-cmf::generic.edit'))
        ->seeRouteIs('laravel-cmf.categories.edit', ['id' => 1])
        ->type('#00FF00', 'color')
        ->press(__('laravel-cmf::generic.save'))
        ->seeRouteIs('laravel-cmf.categories.index')
        ->see('#00FF00');
    }

    public function testFormfieldRadiobtn()
    {
        $this->createBreadForFormfield('text', 'radio_btn', json_encode([
            'default' => 'radio1',
            'options' => [
                'radio1' => 'Foo',
                'radio2' => 'Bar',
            ],
        ]));

        $this->visitRoute('laravel-cmf.categories.create')
        ->select('radio1', 'radio_btn')
        ->press(__('laravel-cmf::generic.save'))
        ->seeRouteIs('laravel-cmf.categories.index')
        ->see('Foo')
        ->click(__('laravel-cmf::generic.edit'))
        ->seeRouteIs('laravel-cmf.categories.edit', ['id' => 1])
        ->select('radio2', 'radio_btn')
        ->press(__('laravel-cmf::generic.save'))
        ->seeRouteIs('laravel-cmf.categories.index')
        ->see('Bar');
    }

    public function testFormfieldSelectDropdown()
    {
        $this->createBreadForFormfield('text', 'select_dropdown', json_encode([
            'default' => 'radio1',
            'options' => [
                'option1' => 'Foo',
                'option2' => 'Bar',
            ],
        ]));

        $this->visitRoute('laravel-cmf.categories.create')
        ->select('option1', 'select_dropdown')
        ->press(__('laravel-cmf::generic.save'))
        ->seeRouteIs('laravel-cmf.categories.index')
        ->see('Foo')
        ->click(__('laravel-cmf::generic.edit'))
        ->seeRouteIs('laravel-cmf.categories.edit', ['id' => 1])
        ->select('option2', 'select_dropdown')
        ->press(__('laravel-cmf::generic.save'))
        ->seeRouteIs('laravel-cmf.categories.index')
        ->see('Bar');
    }

    public function testFormfieldFile()
    {
        $this->createBreadForFormfield('text', 'file');
        $file = UploadedFile::fake()->create('test.txt', 1);
        $this->visitRoute('laravel-cmf.categories.create')
        ->attach([$file->getPathName()], 'file[]')
        ->press(__('laravel-cmf::generic.save'))
        ->seeRouteIs('laravel-cmf.categories.index')
        ->visitRoute('laravel-cmf.categories.create')
        ->press(__('laravel-cmf::generic.save'))
        ->seeRouteIs('laravel-cmf.categories.index')
        ->seeInDatabase('categories', [
            'file' => '[]',
        ]);
    }

    public function testFormfieldFilePreserve()
    {
        $this->createBreadForFormfield('text', 'file', json_encode([
            'preserveFileUploadName' => true,
        ]));
        $file = UploadedFile::fake()->create('test.txt', 1);
        $this->visitRoute('laravel-cmf.categories.create')
        ->attach([$file->getPathName()], 'file[]')
        ->press(__('laravel-cmf::generic.save'))
        ->seeRouteIs('laravel-cmf.categories.index')
        ->visitRoute('laravel-cmf.categories.create')
        ->press(__('laravel-cmf::generic.save'))
        ->seeRouteIs('laravel-cmf.categories.index')
        ->seeInDatabase('categories', [
            'file' => '[]',
        ]);
    }

    private function createBreadForFormfield($type, $name, $options = '')
    {
        Schema::dropIfExists('categories');
        Schema::create('categories', function ($table) use ($type, $name) {
            $table->bigIncrements('id');
            $table->{$type}($name)->nullable();
            $table->timestamps();
        });

        // Delete old BREAD
        $this->delete(route('laravel-cmf.bread.delete', ['id' => DataType::where('name', 'categories')->first()->id]));

        // Create BREAD
        $this->visitRoute('laravel-cmf.bread.create', ['table' => 'categories'])
        ->select($name, 'field_input_type_'.$name)
        ->type($options, 'field_details_'.$name)
        ->type('XADMIN\\LaravelCmf\\Models\\Category', 'model_name')
        ->press(__('laravel-cmf::generic.submit'))
        ->seeRouteIs('laravel-cmf.bread.index');

        // Attach permissions to role
        Auth::user()->role->permissions()->syncWithoutDetaching(Permission::all()->pluck('id'));
    }
}
