<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\LunarTimeRequest;
use App\Models\Lunar;
use Symfony\Component\HttpFoundation\Response;


/**
 * @group
 *
 * Shipment Microservice
 * 
 */
 
class LunarController extends Controller
{   
    /**
     *
     * [$headers this will hold arrays of request header]
     * @var string
     */
    protected $headers;

    /**
     * [$host this will hold the host where the API is being called]
     * @var string
     */
    protected $host;

    /**
     * @title [$userAgent we want to know the medium through which the API is being consumed]
     * @var [type]
     */
    protected $userAgent;

    /**
    *  @response 200 {
    *  "status_code" : 200,
    *  "status" : "success",
    *   "lunar_datetime": "54-11-01 ∇ 13:22:44 LST. The day name is CERNAN"
    *   }
    *
    * @response 422 {
    * "utc_date": [
    *     "UTC datetime is required"
    * ]
    * }
    *
    * @response 422 {
    * "utc_date":[
    *     "Valid UTC (y/m/d h:m:s) date required. Example (2021-01-05 12:10:34)"
    * ]
    * }
    */
    
    public function store(LunarTimeRequest $request)
    {
        $this->headers = $request->headers->all();
        $this->host = $this->headers['host'][0];
        $this->userAgent = $this->headers["user-agent"][0];

        // validate that the required parameters are properly entered
        // this ensure that no sql injection and all required parameters 
        // are entered before submitting to the database
        if($request->validated()){

            // storing the data into the database uncomment if you want to store the data to a database
           
           //$this->storeToDatabase($this->host, $this->userAgent, $this->convertToLunar($request->utc_date), $request->utc_date);

            return response()->json([
                "status_code" => 200,
                "status" => "success",
                "lunar_datetime" => $this->convertToLunar($request->utc_date)
            ], 200)->setStatusCode(Response::HTTP_OK);
        }
    }

    
    /**
     * [storeToDatabase description]
     * @param  [string] $host         [description]
     * @param  [string] $userAgent    [description]
     * @param  [lunar datetime] $lunarTime    [description]
     * @param  [UTC datetime] $shippingTime [description]
     * @return []               [description]
     */
    private function storeToDatabase($host, $userAgent, $lunarTime, $shippingTime):void
    {
        Lunar::create([
            'shipping_time'=> $shippingTime,
            'arrival_time'=>$lunarTime, 
            'host'=> $host,
            'user_agent'=> $userAgent
        ]);
    }


    /**
    * [convertToLunar this accepts a UTC time format and convert it to LUNAR STANDARD TIME]
    * [type] $utcDate [it holds the UTC time format]
    * We need to first get the Lunar epoch UTC time 1969-07-21 02:56:15 
    * Convert it to UNIX TIME format (14159025) strtotime(1969-07-21 02:56:15)
    * 1 Lunar second is 0.9843529666671 second on Earth
    * 1 Lunar minute is 60 Lunar seconds
    * 1 Lunar hour is 60 Lunar minutes
    * 1 Lunar cycle is 24 Lunar hours
    * 1 Lunar day is 30 Lunar cycles
    * 1 Lunar year is 12 Lunar days . Calculation is done by multiplying Lunar year * Lunar day * lunar cycle * lunar hour * lunar minute (12 * 30 * 24 * 60 * 60) = 31104000
    *
    * The days are named after the twelve men who walked on the Moon.
     *@param $fourDays the number of day from wherehouse to earth earth space station and from earth spac station to Lunar colony
     */
    private function convertToLunar($utc_date, $fourDays = (96 * 60 * 60), $lunarDateFormat = ("Y-d-C ! H:i:S T. D"))
    {
        // get the first Lunar datetime and store it to $lunarEpoch valriable
        // there is a preceeding - symbol, we used str_replace to remove the - symbol
        $lunarEpoch = str_replace("-", "", strtotime("1969-07-21 02:56:15"));

        // $numberOfDaysFromEarth is the UTC datetime value entered by 
        // the user plus the number of days it would take for shiping from 
        // earth to lunar colony 
        $numberOfDaysFromEarth = strtotime($utc_date) + $fourDays;

        // the shipping unix time is number of days from Earth plus the lunar 
        // epoch unix time
        $shippingUnix = $numberOfDaysFromEarth + $lunarEpoch;

        // the lunar time is converted to an integer after diving the 
        // $shippingUnix by lunar seconds
        $lunar_time = (int) ($shippingUnix / 0.984352966667);

        // we multiply the lunar yea * lunar day * lunar cycle * lunar hour * lunar minute 
        $lunar_year_day_cycle_hour_minute = (12 * 30 * 24 * 60 * 60);
        
        // calculate the lunar year
        $years = floor($lunar_time / ($lunar_year_day_cycle_hour_minute)) + 1;

        // calculate the lunar days
        $days = floor($lunar_time % ($lunar_year_day_cycle_hour_minute) / (30*24*60*60)) + 1;

        // calculate the lunar cycle
        $cycles = floor($lunar_time % (30*24*60*60) / (24*60*60)) + 1;

        //calculate the lunar hours
        $hours = floor($lunar_time % (24*60*60) / 3600);

        // calculate the lunar minutes
        $minutes = floor($lunar_time % (60*60) / 60);

        // calculate the lunar seconds
        $seconds = floor($lunar_time % (60));

        // we passed the returned arrays values from 
        // lunarDate($lunar_time, $years, $days, $cycles, $hours, $minutes, $seconds) 
        // method
        $dateTimeArray = 
        $this->lunarDate($lunar_time, $years, $days, $cycles, $hours, $minutes, $seconds);

        // we returned final lunar datetime and the name of the Lunar Day
        return str_replace(array_keys($dateTimeArray), array_values($dateTimeArray), $lunarDateFormat);
    }


    /**
     * [lunarDate description]
     * @param  [int] $lunar_time [description]
     * @param  [double] $years      [get the years value]
     * @param  [double] $days       [holds the lunar days value]
     * @param  [double] $cycles     [holds the lunar cycle value]
     * @param  [double] $hours      [holds the lunar hours value]
     * @param  [double] $minutes    [holds the lunar minutes value]
     * @param  [double] $seconds    [holds the lunar seconds value]
     * @return [array]             [arrays of possible Lunar time format]
     */
    private function lunarDate($lunar_time, $years, $days, $cycles, $hours, $minutes, $seconds)
    {
        return [
            "s" => $lunar_time,
            "y" => $years,
            "j" => $days,
            "c" => $cycles,
            "G" => $hours,
            "n" => $minutes,
            "?" => $seconds,
            "Y" => sprintf("%02d", $years),
            "d" => sprintf("%02d", $days),
            "C" => sprintf("%02d", $cycles),
            "H" => sprintf("%02d", $hours),
            "i" => sprintf("%02d", $minutes),
            "S" => sprintf("%02d", $seconds),
            "z" => $days * 30 + $cycles,
            "g" => $hours % 12 + 1,
            "h" => sprintf("%02d", $hours % 12 + 1),
            "!" => "∇", //&#9661
            "T" => "LST",
            "D" => "The day name is " .strtoupper($this->lunarDay()[$days - 1]),
        ];
    }


    /**
     * [lunarDay this holds the names of the 12 lunar days]
     * @return array
     */
    private function lunarDay()
    {
        return [
            "Armstrong", "Aldrin", "Conrad", "Bean", "Shepard", "Mitchell", "Scott", "Irwin", "Young", "Duke", "Cernan", "Schmitt"
        ];
    }
}
