<?php

/**
 * Created by PhpStorm.
 * User: ssshenkie
 * Date: 5/17/14
 * Time: 1:29 AM
 */
class QuestionsController extends BaseController
{

    private $question = [
        [
            'phrase' => 'Do you like reading?',
            'answers' => [
                'no' => [
                    'book_store',
                    'library'
                ]
            ]
        ],
        [
            'phrase' => 'Do you have a car?',
            'answers' => [
                'no' => [
                    'parking',
                    'car_repair',
                    'car_wash',
                    'gas_station'
                ],
                'yes' => [
                    'car_dealer'
                ]
            ]
        ],
        [
            'phrase' => 'Do you like to party?',
            'answers' => [
                'no' => [
                    'night_club',
                    'bar'
                ]
            ]

        ],
        [
            'phrase' => 'Do you like drinking coffee?',
            'answers' => [
                'no' => [
                    'cafe'
                ]
            ]
        ],
        [
            'phrase' => 'Do you like to go shopping?',
            'answers' => [
                'no' => [
                    'shopping_mall',
                    'store',
                    'shoe_store'
                ]
            ]
        ],
        [
            'phrase' => 'Are you having health problems?',
            'answers' => [
                'no' => [
                    'dentist',
                    'hospital',
                    'doctor'
                ]
            ]
        ],
        [
            'phrase' => 'Do you like animals?',
            'answers' => [
                'no' => [
                    'pet_store',
                    'zoo'
                ]
            ]

        ],

        [
            'phrase' => 'Do you own a pet?',
            'answers' => [
                'no' => [
                    'veterinary_care'
                ]
            ]
        ],
        [
            'phrase' => 'Do you like to cook?',
            'answers' => [
                'no' => [
                    'meal_delivery',
                    'meal_takeaway'
                ]
            ]

        ],
        [
            'phrase' => 'Do you use the public transport often?',
            'answers' => [
                'no' => [
                    'taxi_stand',
                    'train_station',
                    'bus_station'
                ]
            ]
        ],
        [
            'phrase' => 'Are you planning to study',
            'answers' => [
                'no' => [
                    'university'
                ]
            ]
        ]

    ];

    public function countExcluded($params) {
        $params = explode(",", $params);
        $types = Type::all();
        foreach($params as $param) {
            $found = false;
            foreach($types as $type) {
                if($type->name == $param) $found = true;
            }
            if(!$found) App::abort(403);
        }

        $builder = new House();
        foreach($params as $param) {
            $builder = $builder->where($param, "=", 0);
        }
        return ["count" => number_format($builder->count())];
    }

    public function show()
    {
        $houses = House::paginate(32);

        $types = Type::all();
        $metas = [];
        foreach ($houses as $house) {
            $meta = [];
            $meta["tags"] = "";
            $meta["class"] = "";
            foreach ($types as $type) {
                $name = $type->name;
                if ($house->$name != 1) continue;
                $meta["tags"] .= $type->display_name . ", ";
                $meta["class"] .= " " . $name;
            }
            if (strlen($meta["tags"]))
                $meta["tags"] = substr($meta["tags"], 0, strlen($meta["tags"]) - 2);
            $metas[$house->id] = $meta;
        }

        return View::make("questions")
            ->with('houses', $houses)
            ->with("meta", $metas)
            ->with("questions", $this->question)
            ->with("total", number_format(House::count()));
    }

} 