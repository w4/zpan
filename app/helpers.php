<?php
function is_route($route)
{
    return Request::is(ltrim(route($route, [], false), '/'));
}
