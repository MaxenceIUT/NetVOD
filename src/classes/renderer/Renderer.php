<?php

namespace iutnc\netvod\renderer;

interface Renderer
{

    const COMPACT = 1;
    const FULL = 2;

    function render(int $mode): string;

}