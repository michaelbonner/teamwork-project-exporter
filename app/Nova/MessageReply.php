<?php

namespace App\Nova;

use App\Models\File;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class MessageReply extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\MessageReply::class;

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
        'id', 'name'
    ];

    public static $perPageViaRelationship = 25;

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
            Text::make('name')->sortable(),
            Code::make('data')->json(),
            BelongsTo::make('message'),
            Text::make('body', function () {
                return $this->data['body'];
            }),
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
