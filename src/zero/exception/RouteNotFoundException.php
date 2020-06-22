<?php
namespace zero\exception;

class RouteNotFoundException extends HttpException
{
    public function __construct()
    {
        parent::__construct(404, 'Route not found!');
    }
}