<?php

namespace App\Activities;

interface ActivityInterface {
    public function action(): string;
    public function metadata(): array;
    public function subjectType(): string;
}