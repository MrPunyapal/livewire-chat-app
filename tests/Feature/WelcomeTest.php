<?php

declare(strict_types=1);

it('returns a successful response', function (): void {
    $this->get('/')->assertOk();
});
