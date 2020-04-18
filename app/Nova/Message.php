<?php

namespace App\Nova;

use App\Models\File;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Message extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Message::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'name'
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),
            Number::make('teamwork_id')->sortable(),
            Text::make('name')->sortable(),
            Code::make('data')->json(),
            Text::make('body', function () {
                return $this->data['html-body'];
            })->asHtml()->showOnIndex(),
            Date::make('created', function () {
                return Carbon::parse($this->data['posted-on']);
            })->showOnIndex()->sortable(),
            Text::make('attachments', function () {
                if (!count($this->data['attachments'])) {
                    return '';
                }
                $returnBody = '<ul>';
                foreach ($this->data['attachments'] as $attachment) {
                    $file = File::where('teamwork_id', $attachment['id'])->first();
                    $returnBody .= "<li>";
                    $returnBody .= "<a href='{$file->publicLink}'>";
                    $returnBody .= $file->name;
                    $returnBody .= "</a>";
                    $returnBody .= "</li>";
                }
                $returnBody .= '</ul>';
                return $returnBody;
            })->asHtml()->showOnIndex(),
            HasMany::make('messageReplies')
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
