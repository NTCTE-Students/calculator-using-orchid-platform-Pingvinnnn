<?php

namespace App\Orchid\Screens;

use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;

class CalculatorScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Калькулятор';
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
                Input::make('expression')
                    ->title('Выражение')
                    ->placeholder('Введите свое выражение'),

                Button::make('Вычислить')
                    ->method('calculate')
                    ->icon('calculator'),
            ]),
            Layout::view('calculator-history'),
        ];
    }

    public function calculate()
    {
        $expression = request('expression');
        $result = eval("return $expression;");

        session()->push('history', "$expression = $result");

        return redirect()->route('platform.calculator');
    }
}
