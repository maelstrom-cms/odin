<?php

namespace App\Http\Controllers;

use Exception;
use App\Website;
use Maelstrom\Panel;
use ReflectionException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Artisan;

class WebsiteController extends Controller
{

    /**
     * @var Panel
     */
    private $panel;

    public function __construct()
    {
        $this->panel = maelstrom(Website::class)
            ->setNameField('url')
            ->setTableHeadings([
                [
                    'name' => 'url',
                    'label' => 'Website',
                    'type' => 'EditLinkColumn',
                    'searchable' => true,
                    'searchColumn' => 'url',
                ],
                [
                    'name' => 'ssl_enabled',
                    'type' => 'BooleanColumn',
                    'label' => 'SSL',
                    'align' => 'center',
                ],
                [
                    'name' => 'dns_enabled',
                    'type' => 'BooleanColumn',
                    'label' => 'DNS',
                    'align' => 'center',
                ],
                [
                    'name' => 'uptime_enabled',
                    'type' => 'BooleanColumn',
                    'label' => 'Uptime',
                    'align' => 'center',
                ],
                [
                    'name' => 'robots_enabled',
                    'type' => 'BooleanColumn',
                    'label' => 'Robots',
                    'align' => 'center',
                ],
                [
                    'name' => 'cron_enabled',
                    'type' => 'BooleanColumn',
                    'label' => 'Crons',
                    'align' => 'center',
                ],
                [
                    'name' => 'crawler_enabled',
                    'type' => 'BooleanColumn',
                    'label' => 'Crawler',
                    'align' => 'center',
                ],
            ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return $this->panel->index('websites-index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return $this->panel->create('websites-create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     * @throws ReflectionException
     */
    public function store(Request $request)
    {
        $request->validate([
            'url' => 'required|url',
            'ssl_enabled' => 'boolean',
            'robots_enabled' => 'boolean',
            'dns_enabled' => 'boolean',
            'uptime_enabled' => 'boolean',
            'uptime_keyword' => 'required_if:uptime_enabled,1',
            'cron_enabled' => 'boolean',
            'cron_key' => 'required_if:cron_enabled,1',
        ]);

        $this->panel->beforeSave(function ($data) use ($request) {
            $data['user_id'] = $request->user()->id;

            return $data;
        });

        $website = $this->panel->store('Website Added - Monitoring will start immediately.');

        $website->runInitialScans();

        return $this->panel->redirect('edit');
    }

    /**
     * Display the specified resource.
     *
     * @param Website $website
     * @return Response
     */
    public function show(Website $website)
    {
        $this->panel->setEntry($website);

        return $this->panel->redirect('edit');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Website $website
     * @return Response
     */
    public function edit(Website $website)
    {
        $this->panel->setEntry($website);

        return $this->panel->edit('websites-show');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Website $website
     * @return Response
     * @throws ReflectionException
     */
    public function update(Request $request, Website $website)
    {
        $request->validate([
            'url' => 'required|url',
            'ssl_enabled' => 'boolean',
            'robots_enabled' => 'boolean',
            'dns_enabled' => 'boolean',
            'uptime_enabled' => 'boolean',
            'uptime_keyword' => 'required_if:uptime_enabled,1',
            'cron_enabled' => 'boolean',
            'cron_key' => 'required_if:cron_enabled,1',
        ]);

        $this->panel->setEntry($website);

        $this->panel->update('Changes saved.');

        return $this->panel->redirect('edit');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Website $website
     * @return void
     * @throws Exception
     */
    public function destroy(Website $website)
    {
        $this->panel->setEntry($website);

        $this->panel->destroy('Website removed.');

        // Artisan::call('horizon:terminate');

        return $this->panel->redirect('index');
    }

    /**
     * @return RedirectResponse|Redirector
     * @throws Exception
     */
    public function bulk()
    {
        $this->panel->handleBulkActions();

        return $this->panel->redirect('index');
    }
}
