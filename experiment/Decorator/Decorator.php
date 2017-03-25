<?php
/**
 * Created by PhpStorm.
 * User: richjones
 * Date: 3/23/17
 * Time: 4:41 AM
 */

interface CarService {
    public function getCost();
}


class BasicInspection implements CarService {
    public function getCost()
    {
        return 19;
    }
}

class OilChange implements CarService {

    /**
     * @var CarService
     */
    private $carService;

    public function __construct(CarService $carService)
    {
        $this->carService = $carService;
    }

    public function getCost()
    {
        return 29 + $this->carService->getCost();
    }
}

class TireRotation implements CarService {

    /**
     * @var CarService
     */
    private $carService;

    public function __construct(CarService $carService)
    {
        $this->carService = $carService;
    }

    public function getCost()
    {
        return 39 + $this->carService->getCost();
    }
}

class CalcTotalCost {

    public $totalCost = 0;

    /**
     * @var array
     */
    private $services;
    /**
     * @var BasicInspection
     */
    private $basicInspection;

    public function __construct(array $services, BasicInspection $basicInspection)
    {
        $this->services = $services;
        $this->basicInspection = $basicInspection;
    }

    public function getCost() {

        /** @var CarService $inst */
        $inst = new $this->basicInspection();

        foreach($this->services as $key => $service)
        {
            // composition; building a class linked list...
            if (class_exists($service)) {
                $inst = (new $service($inst));
            }
        }

        return $inst->getCost();
    }
}

$carServicesArray = ['OilChange', 'TireRotation'];

$sum = (new CalcTotalCost($carServicesArray, new BasicInspection()))->getCost();

echo "the total cost is {$sum}\n";


class SomeServer {

    static private $basicInspection;
    static private $services;
    static private $classTotalCost;

    static private $totalCost;

    /**
     * @param $services
     * @param BasicInspection $basicInspection
     * @param string $classTotalCost
     * @return mixed
     */
    static public function setTotalCostProps($services, BasicInspection $basicInspection, string $classTotalCost)
    {
        self::$services = $services;
        self::$basicInspection = $basicInspection;
        self::$classTotalCost = $classTotalCost;

        /** @var CalcTotalCost $tmp */
        $tmp = new self::$classTotalCost(self::$services, self::$basicInspection);
        return $tmp->getCost();
    }

    public function __toString()
    {
        return (string)self::$totalCost;
    }

}

echo "the total cost is " . SomeServer::setTotalCostProps($carServicesArray, new BasicInspection(), "CalcTotalCost") . "\n";











