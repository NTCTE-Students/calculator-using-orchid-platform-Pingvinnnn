<?php

namespace App\Orchid\Screens;

use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

class ConvertedScreenMass extends Screen
{
    protected $units = [
        'длина' => [
            'метр' => 1,
            'километр' => 0.001,
            'миль' => 0.000621371,
        ],
        'масса' => [
            'грамм' => 1,
            'килограмм' => 0.001,
            'фунт стерлинга' => 0.00220462,
        ],
    ];
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Request $request): array
    {
        $type = $request->get('type', 'масса');
        return [
            'unitTypes' => $this->getUnitTypes(),
            'units' => $this->getUnits($type),
            'selectedType' => $type,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Конвертирование массы';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): array
    {
        return [];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::rows([
                Select::make('type')
                    ->options($this->getUnitTypes())
                    ->title('Тип измерения')
                    ->required()
                    ->value(request('type', 'масса'))
                    ->onChange('updateUnits'),

                Select::make('from_unit')
                    ->options($this->getUnits(request('type', 'масса')))
                    ->title('Из какой единицы измерения')
                    ->required(),

                Input::make('value')
                    ->title('Значение')
                    ->type('number')
                    ->required(),

                Select::make('to_unit')
                    ->options($this->getUnits(request('type', 'масса')))
                    ->title('В какую единицы изменения')
                    ->required(),

                Button::make('Конвертировать')
                    ->method('convert')
                    ->icon('refresh'),
            ]),
            Layout::view('converter-result'),
        ];
    }

    public function convert(Request $request)
    {
        $type = $request->input('type');
        $from = $request->input('from_unit');
        $to = $request->input('to_unit');
        $value = $request->input('value');

        if (!isset($this->units[$type][$from]) || !isset($this->units[$type][$to])) {
            session()->flash('result', 'Недопустимый тип или единица измерения');
            return redirect()->route('platform.convmass');
        }

        $result = $value * ($this->units[$type][$to] / $this->units[$type][$from]);

        session()->flash('result', "$value $from(а) переведенно в $result $to(ов)");

        return redirect()->route('platform.convmass', ['type' => $type]);
    }

    private function getUnitTypes()
    {
        $types = [];
        foreach ($this->units as $type => $units) {
            $types[$type] = ucfirst($type);
        }
        return $types;
    }

    private function getUnits($type)
    {
        $units = [];
        foreach ($this->units[$type] as $unit => $value) {
            $units[$unit] = ucfirst($unit);
        }
        return $units;
    }
}
