<?php

test('public employee registration is disabled', function () {
    $this->get('/register')->assertNotFound();
});
