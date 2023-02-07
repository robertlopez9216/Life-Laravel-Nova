<?php

namespace App\Nova;

use Wqa\LinkField\Link;
use Illuminate\Http\Request;
use App\Nova\Actions\ForceUpdate;
use Wqa\DeepLinkTool\DeepLinkTool;
use Wqa\NovaExtendFields\Fields\Text;
use Wqa\NovaExtendFields\Fields\Image;
use Wqa\NovaExtendFields\Fields\Textarea;
use Wqa\NovaExtendFields\Fields\BelongsTo;
use Wqa\NovaExtendFields\Fields\MorphMany;
use Wqa\NovaExtendFields\MultiSelectField;
use Wqa\NovaExtendFields\Fields\BooleanSwitch;
use Wqa\NovaSortableTableResource\Concerns\SortsIndexEntries;

class Blend extends Resource
{
    use SortsIndexEntries;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Blend';

    /**
     * The logical group associated with the resource.
     *
     * @var string
     */
    public static $group = ['Solutions'];

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
        'id',
        'name',
    ];

    /**
     * Return the fields for the index view.
     *
     * @return array
     */
    public function fieldsForIndex()
    {
        if (request()->editMode) {
            return [
                Text::make('Name'),

                Textarea::make('Fact'),

                BooleanSwitch::make('Featured', 'is_featured'),
            ];
        }

        return [
            Image::make('Photo', 'image_url', 's3'),

            Link::make('Name')->href('/admin/resources/blends/' . $this->id)->sortable(),

            Textarea::make('Fact'),

            BooleanSwitch::make('Featured', 'is_featured')->hideLabel(),
        ];
    }

    /**
     * Return the fields for left column.
     *
     * @return array
     */
    protected function leftColumnFields()
    {
        return [
            BooleanSwitch::make('Make Featured', 'is_featured')->hideLabel(),

            Text::make('Name')
                ->rules('required'),

            Textarea::make('Fact', 'fact')
                ->rules('required'),

            Text::make('Emotion 1', 'emotion_1')
                ->rules('required'),

            Text::make('Emotion 2', 'emotion_2')
                ->rules('required'),

            Text::make('Emotion 3', 'emotion_3')
                ->rules('required'),

            BelongsTo::make('Safety Information', 'safetyInformation'),

            MultiSelectField::make('Ingredients', 'ingredients')
                ->withMeta(['selectedOptions' => $this->ingredients->pluck('name', 'id')->mapToOptions()])
                ->options(\App\Oil::orderBy('name')->pluck('name', 'id')->toArray())
                ->excludeOnUpdate()
                ->onlyOnForms(),

            MorphMany::make('Regional Names', 'regionalNames')
                ->withMeta(['poly' => [
                    'poly_id' => 'regionable_id',
                    'poly_type' => 'regionable_type',
                    'id' => $this->id,
                    'type' => 'App\\Blend'
                ]]),

            MorphMany::make('Top Uses', 'usages', TopUse::class)
        ];
    }

    /**
     * Return the fields for right column.
     *
     * @return array
     */
    protected function rightColumnFields()
    {
        return [
            Image::make('Photo', 'image_url', 's3')
                ->deletable(false)
                ->rules(['image', 'max:2000'])
                ->creationRules(['required'])
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
        return [
            new ForceUpdate(),
        ];
    }

    public function tools()
    {
        return [
            DeepLinkTool::make()->forResource('Blend', $this->uuid ?? ''),
        ];
    }
}
