<?php
namespace zero\exceptions;

class RouteNotFoundException extends HttpException
{
    public function __construct()
    {
        parent::__construct(404, 'Route not found!');
    }
}