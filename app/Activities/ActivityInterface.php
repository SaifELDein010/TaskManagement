<?php

namespace App\Activitiess;

interface Activity {
    public function action(): string;
    public function metadata(): array;
    public function subjectType(): string;
}