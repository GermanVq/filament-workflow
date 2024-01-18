<?php

namespace App\Filament\Resources\WorkflowResource\Actions;

use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\Concerns\CanCustomizeProcess;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Database\Eloquent\Model;

class WorkflowActionButton extends Action
{
    use CanCustomizeProcess;


    public static function buttons($record = null)
    {
        $actions = $record->latestWorkflowRecord?->workflowStep?->workflowActions?->map(function ($action)  {
            if ($action->name == 'assign') {
                return self::assignAction($action);
            }
            if ($action->name == 'accept') {
                return self::acceptAction($action);
            }
            if ($action->name == 'reject') {
                return self::rejectAction($action);
            }
        })->toArray();
        return  is_array($actions) ? $actions : [];

    }


    public static function assignAction($action)  {
        return Action::make()
        ->name($action->name)
        ->color($action->color)
        ->label($action->label)
        ->form([
            Select::make('user_assigned_id')
                ->label('Usuario asignado')
                ->options(User::query()->pluck('name', 'id'))
                ->required(),
        ])
        ->action(fn (array $data, Model $record) => $record->transitionTo(['action'=> $action,'user_assigned_id'=> $data['user_assigned_id']]));
    }

    public static function acceptAction($action)  {
        return Action::make()
        ->name($action->name)
        ->color($action->color)
        ->label($action->label)
        ->requiresConfirmation()
        ->action(fn (Model $record) => $record->transitionTo(['action'=> $action]));
    }

    public static function rejectAction($action)  {
        return Action::make()
        ->name($action->name)
        ->color($action->color)
        ->label($action->label)
        ->form([
            Select::make('rejectionType')
                ->label('Tipo de rechazo')
                ->options(
                   [ 'Otra'=>'Otra',]
                )
                ->required(),
            Textarea::make('rejectionReason')->label('Motivo de rechazo'),
        ])
        ->action(fn (array $data, Model $record) => $record->transitionTo(['action'=> $action,' rejection_type'=> $data['rejectionType'], 'rejection_reason'=> $data['rejectionReason']]));
    }


}

