<?php

namespace App\Support;

class PackageItineraryCatalog
{
    /**
     * @param  array{name: string, category: string, location: string, duration: string}  $package
     * @return array{
     *     overview: string,
     *     itinerary: list<array{
     *         day: string,
     *         title: string,
     *         description: string,
     *         meals?: string,
     *         destinations?: string
     *     }>,
     *     includes: list<string>,
     *     excludes: list<string>
     * }|null
     */
    public function for(array $package): ?array
    {
        $definition = $this->definitions()[$package['name']] ?? null;

        if ($definition === null) {
            return null;
        }

        $itinerary = [];

        foreach ($definition['days'] as $index => $day) {
            $itinerary[] = [
                'day' => sprintf('Day %d', $index + 1),
                'title' => $day['title'],
                'description' => $day['description'],
                'meals' => $day['meals'] ?? null,
                'destinations' => $day['destinations'] ?? null,
            ];
        }

        return [
            'overview' => $definition['overview'],
            'itinerary' => $itinerary,
            'includes' => $definition['includes'] ?? $this->defaultIncludes($package['category']),
            'excludes' => $definition['excludes'] ?? $this->defaultExcludes(),
        ];
    }

    /**
     * @return array<string, array{
     *     overview: string,
     *     days: list<array{title: string, description: string, meals?: string, destinations?: string}>,
     *     includes?: list<string>,
     *     excludes?: list<string>
     * }>
     */
    private function definitions(): array
    {
        return [
            ...$this->safariDefinitions(),
            ...$this->trekkingDefinitions(),
            ...$this->dayTripDefinitions(),
        ];
    }

    /**
     * @return array<string, array{overview: string, days: list<array{title: string, description: string, meals?: string, destinations?: string}>, includes?: list<string>}>
     */
    private function safariDefinitions(): array
    {
        $safariIncludes = [
            'Park entry fees',
            'Professional English speaking safari guide',
            'Private 4x4 Land Cruiser with pop up roof',
            'Accommodation as listed for the tour style',
            'Meals as shown in the daily itinerary',
            'Bottled drinking water in the vehicle',
            'Airport or hotel transfers in Arusha on request',
        ];

        return [
            '2 Days Tarangire and Ngorongoro Safari' => [
                'overview' => 'A short Northern Circuit safari that pairs Tarangire National Park with a full crater day in Ngorongoro. You leave Arusha in the morning, sleep near the highlands, and return the next afternoon with classic elephant, baobab, and Big Five viewing.',
                'includes' => $safariIncludes,
                'days' => [
                    [
                        'title' => 'Arusha to Tarangire National Park',
                        'destinations' => 'Arusha, Tarangire National Park',
                        'meals' => 'Lunch, Dinner',
                        'description' => 'Your guide collects you in Arusha after breakfast and drives south west to Tarangire National Park, about two hours away. The park is famous for large elephant herds, ancient baobab trees, and wildlife concentrated along the Tarangire River in the dry season. You take a picnic lunch inside the park and continue game driving through woodland and open grassland until late afternoon. Overnight is at a lodge or camp near Tarangire or in the Karatu area, depending on the booking.',
                    ],
                    [
                        'title' => 'Ngorongoro Crater to Arusha',
                        'destinations' => 'Ngorongoro Crater, Arusha',
                        'meals' => 'Breakfast, Lunch',
                        'description' => 'After an early breakfast you enter the Ngorongoro Conservation Area and descend onto the crater floor. Ngorongoro holds a dense mix of lion, buffalo, elephant, hippo, and seasonal flamingo around the soda lake, with a strong chance of seeing black rhino. You picnic near the hippo pool, then climb back to the rim and drive to Arusha, arriving in the late afternoon for hotel or airport drop off.',
                    ],
                ],
            ],
            '3 Days Tarangire, Manyara and Ngorongoro Safari' => [
                'overview' => 'Three park days covering Tarangire, Lake Manyara, and Ngorongoro Crater. The route stays on the classic northern safari loop with overnight stops near Mto wa Mbu or Karatu before the final crater descent.',
                'includes' => $safariIncludes,
                'days' => [
                    [
                        'title' => 'Arusha to Tarangire National Park',
                        'destinations' => 'Arusha, Tarangire National Park',
                        'meals' => 'Lunch, Dinner',
                        'description' => 'Depart Arusha after breakfast for Tarangire National Park. Spend the day on game drives among baobabs and riverine woodland, watching for elephant, zebra, giraffe, buffalo, and predators that hunt near the water. Picnic lunch is served in the park. In the evening you continue to your lodge or tented camp near Lake Manyara or Karatu.',
                    ],
                    [
                        'title' => 'Lake Manyara National Park',
                        'destinations' => 'Lake Manyara National Park',
                        'meals' => 'Breakfast, Lunch, Dinner',
                        'description' => 'Enter Lake Manyara National Park for a full morning and afternoon of wildlife viewing. The park sits under the Rift Valley escarpment and mixes groundwater forest, open floodplain, and alkaline lake shore. Look for tree climbing lions when conditions favour them, large troops of baboon, hippo, and rich birdlife including pelicans and flamingos. Overnight stays in the Karatu highlands.',
                    ],
                    [
                        'title' => 'Ngorongoro Crater to Arusha',
                        'destinations' => 'Ngorongoro Crater, Arusha',
                        'meals' => 'Breakfast, Lunch',
                        'description' => 'Drive to the crater rim after breakfast and descend for a half day game drive on the crater floor. Guides work the grasslands, marshes, and forest edge for lion, hyena, elephant, buffalo, and rhino. After lunch you leave the crater and return to Arusha by late afternoon.',
                    ],
                ],
            ],
            '4 Days Camping Safari, Tarangire, Serengeti and Ngorongoro' => $this->fourDayNorthernCircuit(
                'camping',
                $safariIncludes
            ),
            '4 Days Lodge Safari, Tarangire, Serengeti and Ngorongoro' => $this->fourDayNorthernCircuit(
                'lodge',
                $safariIncludes
            ),
            '5 Days Camping Safari, Tarangire, Serengeti and Ngorongoro' => $this->fiveDayNorthernCircuit(
                'camping',
                $safariIncludes
            ),
            '5 Days Lodge Safari, Tarangire, Serengeti and Ngorongoro' => $this->fiveDayNorthernCircuit(
                'lodge',
                $safariIncludes
            ),
            '6 Days Camping Safari, Tarangire, Serengeti, Ngorongoro and Lake Eyasi' => [
                'overview' => 'A six day camping safari through Tarangire, Serengeti, and Ngorongoro, finished with a cultural visit at Lake Eyasi. Extra time in Serengeti improves wildlife viewing before the crater day and Hadzabe or Datoga community visit.',
                'includes' => [
                    ...$safariIncludes,
                    'Lake Eyasi cultural visit with local guide',
                ],
                'days' => [
                    [
                        'title' => 'Arusha to Tarangire National Park',
                        'destinations' => 'Arusha, Tarangire National Park',
                        'meals' => 'Lunch, Dinner',
                        'description' => 'Leave Arusha for Tarangire National Park and spend the day on game drives along the river and among the baobabs. Elephant numbers are often high here, and the park also holds lion, leopard, zebra, wildebeest, and many bird species. Overnight camping near Tarangire or in the Manyara area.',
                    ],
                    [
                        'title' => 'Tarangire to Serengeti National Park',
                        'destinations' => 'Tarangire, Serengeti National Park',
                        'meals' => 'Breakfast, Lunch, Dinner',
                        'description' => 'After breakfast you drive through the Ngorongoro Conservation Area and enter Serengeti National Park via Naabi Hill. The journey itself is a game drive across short grass plains. You arrive in the central Serengeti for an afternoon drive around Seronera before camping inside or near the park.',
                    ],
                    [
                        'title' => 'Full day in Serengeti National Park',
                        'destinations' => 'Serengeti National Park',
                        'meals' => 'Breakfast, Lunch, Dinner',
                        'description' => 'A full day of game drives in the Serengeti. Your guide follows fresh tracks and seasonal wildlife movement across kopjes, river lines, and open plains. Expect lion, cheetah, elephant, giraffe, and large herds of plains game. Picnic lunch is taken in the bush so you stay out through the best light.',
                    ],
                    [
                        'title' => 'Serengeti to Ngorongoro Highlands',
                        'destinations' => 'Serengeti National Park, Ngorongoro Conservation Area',
                        'meals' => 'Breakfast, Lunch, Dinner',
                        'description' => 'Enjoy a morning game drive in Serengeti, then drive east toward the Ngorongoro highlands. You camp on or near the crater rim, with cool air and wide views over the caldera. The evening is free to rest before the crater descent.',
                    ],
                    [
                        'title' => 'Ngorongoro Crater game drive',
                        'destinations' => 'Ngorongoro Crater',
                        'meals' => 'Breakfast, Lunch, Dinner',
                        'description' => 'Descend into Ngorongoro Crater after sunrise for a long game drive on the crater floor. This is one of the best places in Tanzania to see black rhino alongside lion, buffalo, elephant, and hippo. After lunch you climb back to the rim and continue to Lake Eyasi for overnight camping.',
                    ],
                    [
                        'title' => 'Lake Eyasi cultural visit to Arusha',
                        'destinations' => 'Lake Eyasi, Arusha',
                        'meals' => 'Breakfast, Lunch',
                        'description' => 'Early morning visit with Hadzabe hunter gatherers near Lake Eyasi, and often a stop with Datoga metal workers depending on local arrangements. You learn how communities live around the soda lake before returning to Arusha in the afternoon.',
                    ],
                ],
            ],
            '7 Days Lodge Safari, Tarangire, Serengeti, Ngorongoro, Lake Eyasi and Manyara' => [
                'overview' => 'A full Northern Circuit lodge safari linking Tarangire, Lake Manyara, Serengeti, Ngorongoro Crater, and Lake Eyasi. The pace allows two nights in Serengeti and a proper cultural morning at Eyasi before the final return to Arusha.',
                'includes' => [
                    ...$safariIncludes,
                    'Lake Eyasi cultural visit with local guide',
                ],
                'days' => [
                    [
                        'title' => 'Arusha to Tarangire National Park',
                        'destinations' => 'Arusha, Tarangire National Park',
                        'meals' => 'Lunch, Dinner',
                        'description' => 'Drive from Arusha to Tarangire National Park for a full afternoon of game drives. Focus on the river corridor where elephants gather and where predators often wait near water. Overnight at a lodge near Tarangire or Karatu.',
                    ],
                    [
                        'title' => 'Lake Manyara National Park',
                        'destinations' => 'Lake Manyara National Park',
                        'meals' => 'Breakfast, Lunch, Dinner',
                        'description' => 'Spend the day in Lake Manyara National Park beneath the Rift Valley wall. Game drives move through forest, floodplain, and lake shore looking for elephant, hippo, giraffe, and birdlife. When sightings allow, guides also search for Manyara’s tree climbing lions. Overnight in Karatu.',
                    ],
                    [
                        'title' => 'Karatu to Serengeti National Park',
                        'destinations' => 'Ngorongoro Conservation Area, Serengeti National Park',
                        'meals' => 'Breakfast, Lunch, Dinner',
                        'description' => 'Travel through the Ngorongoro highlands and across the plains into Serengeti National Park. Stop for wildlife along the way and continue to the central Seronera area for an afternoon game drive. Overnight in a Serengeti lodge or tented camp.',
                    ],
                    [
                        'title' => 'Full day in Serengeti National Park',
                        'destinations' => 'Serengeti National Park',
                        'meals' => 'Breakfast, Lunch, Dinner',
                        'description' => 'Explore Serengeti from early morning to late afternoon. Your guide adjusts the route for migration herds, big cats on kopjes, or riverine woodland depending on the season. Lunch is a picnic so you remain in good wildlife areas through the middle of the day.',
                    ],
                    [
                        'title' => 'Serengeti to Ngorongoro Crater',
                        'destinations' => 'Serengeti National Park, Ngorongoro Crater',
                        'meals' => 'Breakfast, Lunch, Dinner',
                        'description' => 'Leave Serengeti after a morning game drive and continue to Ngorongoro. Descend into the crater for wildlife viewing on the floor, then climb to a lodge on the rim or in Karatu for the night.',
                    ],
                    [
                        'title' => 'Lake Eyasi cultural visit',
                        'destinations' => 'Lake Eyasi',
                        'meals' => 'Breakfast, Lunch, Dinner',
                        'description' => 'Drive to Lake Eyasi for a cultural day with Hadzabe communities and, where arranged, Datoga blacksmiths. The morning is spent walking and learning local hunting and gathering skills before returning to your lodge near Karatu or Eyasi.',
                    ],
                    [
                        'title' => 'Return to Arusha',
                        'destinations' => 'Karatu, Arusha',
                        'meals' => 'Breakfast, Lunch',
                        'description' => 'After breakfast you drive back to Arusha with a stop for lunch on the way. Drop off is at your hotel or Kilimanjaro International Airport according to your flight schedule.',
                    ],
                ],
            ],
        ];
    }

    /**
     * @param  list<string>  $includes
     * @return array{overview: string, days: list<array{title: string, description: string, meals?: string, destinations?: string}>, includes: list<string>}
     */
    private function fourDayNorthernCircuit(string $style, array $includes): array
    {
        $stay = $style === 'camping' ? 'camping' : 'lodge';

        return [
            'overview' => sprintf(
                'A classic four day Northern Circuit safari visiting Tarangire, Serengeti, and Ngorongoro Crater with %s accommodation. This is the shortest route that still reaches the Serengeti plains before a full crater morning.',
                $stay
            ),
            'includes' => $includes,
            'days' => [
                [
                    'title' => 'Arusha to Tarangire National Park',
                    'destinations' => 'Arusha, Tarangire National Park',
                    'meals' => 'Lunch, Dinner',
                    'description' => sprintf(
                        'Depart Arusha after breakfast and enter Tarangire National Park for a full day of game drives. Search for elephant herds among the baobabs, plus lion, zebra, wildebeest, and giraffe along the river. Overnight %s near the park or in the Karatu area.',
                        $stay
                    ),
                ],
                [
                    'title' => 'Tarangire to Serengeti National Park',
                    'destinations' => 'Serengeti National Park',
                    'meals' => 'Breakfast, Lunch, Dinner',
                    'description' => sprintf(
                        'Drive through the Ngorongoro Conservation Area and into Serengeti National Park. Game viewing continues as the landscape opens into endless grassland. Arrive in the Seronera area for an afternoon drive and overnight %s in or beside the park.',
                        $stay
                    ),
                ],
                [
                    'title' => 'Serengeti to Ngorongoro Highlands',
                    'destinations' => 'Serengeti National Park, Ngorongoro Conservation Area',
                    'meals' => 'Breakfast, Lunch, Dinner',
                    'description' => sprintf(
                        'Spend the morning on game drives in central Serengeti, then travel toward the Ngorongoro highlands with wildlife viewing en route. Overnight %s on the crater rim or nearby, ready for an early descent the next day.',
                        $stay
                    ),
                ],
                [
                    'title' => 'Ngorongoro Crater to Arusha',
                    'destinations' => 'Ngorongoro Crater, Arusha',
                    'meals' => 'Breakfast, Lunch',
                    'description' => 'Descend into Ngorongoro Crater after breakfast for a half day game drive. The enclosed crater floor concentrates buffalo, elephant, lion, hippo, and often black rhino. After picnic lunch you climb to the rim and return to Arusha.',
                ],
            ],
        ];
    }

    /**
     * @param  list<string>  $includes
     * @return array{overview: string, days: list<array{title: string, description: string, meals?: string, destinations?: string}>, includes: list<string>}
     */
    private function fiveDayNorthernCircuit(string $style, array $includes): array
    {
        $stay = $style === 'camping' ? 'camping' : 'lodge';

        return [
            'overview' => sprintf(
                'Five days on the Northern Circuit with Tarangire, two nights in Serengeti, and Ngorongoro Crater. %s accommodation keeps you close to the parks while the extra Serengeti night improves predator and migration viewing.',
                ucfirst($stay)
            ),
            'includes' => $includes,
            'days' => [
                [
                    'title' => 'Arusha to Tarangire National Park',
                    'destinations' => 'Arusha, Tarangire National Park',
                    'meals' => 'Lunch, Dinner',
                    'description' => sprintf(
                        'Leave Arusha for Tarangire National Park and spend the day among baobabs and river woodland. Elephant sightings are a highlight, with good chances for big cats and large herds of plains game. Overnight %s near Tarangire or Manyara.',
                        $stay
                    ),
                ],
                [
                    'title' => 'Tarangire to Serengeti National Park',
                    'destinations' => 'Serengeti National Park',
                    'meals' => 'Breakfast, Lunch, Dinner',
                    'description' => sprintf(
                        'Cross the highlands and enter Serengeti National Park through Naabi Hill Gate. Afternoon game drives focus on the central plains and Seronera River area. Overnight %s inside or near the park.',
                        $stay
                    ),
                ],
                [
                    'title' => 'Full day in Serengeti National Park',
                    'destinations' => 'Serengeti National Park',
                    'meals' => 'Breakfast, Lunch, Dinner',
                    'description' => 'A full day exploring Serengeti. Morning and afternoon drives may follow lion prides, cheetah on open flats, or seasonal wildebeest and zebra movement. Picnic lunch keeps you in the field during the quiet midday hours when leopard often rest in riverine trees.',
                ],
                [
                    'title' => 'Serengeti to Ngorongoro Highlands',
                    'destinations' => 'Serengeti National Park, Ngorongoro Conservation Area',
                    'meals' => 'Breakfast, Lunch, Dinner',
                    'description' => sprintf(
                        'Final morning game drive in Serengeti, then drive to the Ngorongoro Conservation Area. You overnight %s on the crater rim or in Karatu with time to rest before the crater day.',
                        $stay
                    ),
                ],
                [
                    'title' => 'Ngorongoro Crater to Arusha',
                    'destinations' => 'Ngorongoro Crater, Arusha',
                    'meals' => 'Breakfast, Lunch',
                    'description' => 'Early descent into Ngorongoro Crater for wildlife viewing across grassland, swamp, and forest edge. After lunch near the hippo pool you leave the crater and continue to Arusha for evening drop off.',
                ],
            ],
        ];
    }

    /**
     * @return array<string, array{overview: string, days: list<array{title: string, description: string, meals?: string, destinations?: string}>, includes?: list<string>}>
     */
    private function trekkingDefinitions(): array
    {
        $trekIncludes = [
            'Kilimanjaro National Park fees and rescue fees',
            'Certified mountain guide, assistant guides, cook, and porters',
            'Mountain tents or hut beds as per the route',
            'All meals and drinking water on the mountain',
            'Camping equipment including mess tent and sleeping mats',
            'Transfers between Moshi or Arusha and the trail gate',
            'Summit certificate on successful ascent',
        ];

        return [
            '5 Days Marangu Route, Kilimanjaro' => [
                'overview' => 'The five day Marangu Route is Kilimanjaro’s hut trail and the shortest standard ascent. You sleep in shared mountain huts and follow the same path up and down via Mandara, Horombo, and Kibo before the midnight push to Uhuru Peak.',
                'includes' => $trekIncludes,
                'days' => [
                    [
                        'title' => 'Marangu Gate to Mandara Hut',
                        'destinations' => 'Marangu Gate (1,860m), Mandara Hut (2,700m)',
                        'meals' => 'Lunch, Dinner',
                        'description' => 'After registration at Marangu Gate you walk through montane rainforest to Mandara Hut at about 2,700m. The trail is well formed and takes four to five hours. Colobus monkeys are often seen in the canopy. Dinner and overnight are in the A-frame hut dormitories.',
                    ],
                    [
                        'title' => 'Mandara Hut to Horombo Hut',
                        'destinations' => 'Mandara Hut, Horombo Hut (3,720m)',
                        'meals' => 'Breakfast, Lunch, Dinner',
                        'description' => 'Leave the forest for heather and moorland as you climb to Horombo Hut at 3,720m. The walk usually takes five to seven hours with open views of both Mawenzi and Kibo on a clear day. Overnight at Horombo.',
                    ],
                    [
                        'title' => 'Horombo Hut to Kibo Hut',
                        'destinations' => 'Horombo Hut, Kibo Hut (4,700m)',
                        'meals' => 'Breakfast, Lunch, Dinner',
                        'description' => 'Cross the alpine desert of the Saddle between Mawenzi and Kibo to reach Kibo Hut at about 4,700m. The day is shorter in distance but feels harder in thin air. Arrive early afternoon, eat an early dinner, and rest before the midnight summit departure.',
                    ],
                    [
                        'title' => 'Kibo Hut to Uhuru Peak to Horombo Hut',
                        'destinations' => 'Uhuru Peak (5,895m), Horombo Hut',
                        'meals' => 'Breakfast, Lunch, Dinner',
                        'description' => 'Start around midnight for the steep scree climb to Gilman’s Point on the crater rim, then continue around the rim to Uhuru Peak at 5,895m. After sunrise photos you descend all the way to Horombo Hut for a long recovery night.',
                    ],
                    [
                        'title' => 'Horombo Hut to Marangu Gate',
                        'destinations' => 'Horombo Hut, Marangu Gate',
                        'meals' => 'Breakfast, Lunch',
                        'description' => 'Descend through moorland and rainforest to Marangu Gate. Collect summit certificates, meet your transfer, and return to Moshi or Arusha for a hot shower and celebration meal.',
                    ],
                ],
            ],
            '6 Days Marangu Route, Kilimanjaro' => [
                'overview' => 'Six days on the Marangu Route adds an acclimatization day at Horombo Hut, which improves summit chances compared with the five day version. Accommodation remains in mountain huts for the whole climb.',
                'includes' => $trekIncludes,
                'days' => [
                    [
                        'title' => 'Marangu Gate to Mandara Hut',
                        'destinations' => 'Marangu Gate (1,860m), Mandara Hut (2,700m)',
                        'meals' => 'Lunch, Dinner',
                        'description' => 'Register at Marangu Gate and trek through rainforest to Mandara Hut. The path is gradual and usually completed in four to five hours. Overnight in the hut dormitory.',
                    ],
                    [
                        'title' => 'Mandara Hut to Horombo Hut',
                        'destinations' => 'Mandara Hut, Horombo Hut (3,720m)',
                        'meals' => 'Breakfast, Lunch, Dinner',
                        'description' => 'Climb into moorland toward Horombo Hut at 3,720m. Views open toward Mawenzi and Kibo as the vegetation thins. Arrive mid afternoon and settle into the hut for two nights.',
                    ],
                    [
                        'title' => 'Acclimatization day at Horombo Hut',
                        'destinations' => 'Horombo Hut, Zebra Rocks',
                        'meals' => 'Breakfast, Lunch, Dinner',
                        'description' => 'Spend a rest and acclimatization day at Horombo. A short hike toward Zebra Rocks or higher toward Mawenzi helps your body adjust, then you return to sleep at the same altitude. This extra night is the main reason the six day Marangu itinerary works better than five days.',
                    ],
                    [
                        'title' => 'Horombo Hut to Kibo Hut',
                        'destinations' => 'Horombo Hut, Kibo Hut (4,700m)',
                        'meals' => 'Breakfast, Lunch, Dinner',
                        'description' => 'Walk across the Saddle to Kibo Hut at 4,700m. Keep a slow pace, drink often, and rest after arrival. Dinner is early so you can sleep before the summit climb.',
                    ],
                    [
                        'title' => 'Kibo Hut to Uhuru Peak to Horombo Hut',
                        'destinations' => 'Uhuru Peak (5,895m), Horombo Hut',
                        'meals' => 'Breakfast, Lunch, Dinner',
                        'description' => 'Leave Kibo around midnight, climb to Gilman’s Point, and continue to Uhuru Peak. After summit time you descend to Horombo Hut for overnight rest.',
                    ],
                    [
                        'title' => 'Horombo Hut to Marangu Gate',
                        'destinations' => 'Horombo Hut, Marangu Gate',
                        'meals' => 'Breakfast, Lunch',
                        'description' => 'Final descent to Marangu Gate through moorland and forest. Certificates are issued at the gate before your transfer back to town.',
                    ],
                ],
            ],
            '6 Days Machame Route, Kilimanjaro' => [
                'overview' => 'The six day Machame Route climbs Kilimanjaro from the south through rainforest, Shira Plateau, Barranco Valley, and Barafu Camp before summit night. It is scenic and popular, with camping each night and a steeper profile than Marangu.',
                'includes' => $trekIncludes,
                'days' => [
                    [
                        'title' => 'Machame Gate to Machame Camp',
                        'destinations' => 'Machame Gate (1,800m), Machame Camp (3,000m)',
                        'meals' => 'Lunch, Dinner',
                        'description' => 'After park formalities at Machame Gate you walk uphill through dense rainforest to Machame Camp at about 3,000m. The trek takes five to seven hours. Porters go ahead to pitch tents before you arrive for dinner.',
                    ],
                    [
                        'title' => 'Machame Camp to Shira Camp',
                        'destinations' => 'Machame Camp, Shira Camp (3,840m)',
                        'meals' => 'Breakfast, Lunch, Dinner',
                        'description' => 'Leave the forest for heather and rocky trails onto the Shira Plateau. Reach Shira Camp at around 3,840m after four to six hours, with improving views of Kibo’s southern icefields.',
                    ],
                    [
                        'title' => 'Shira Camp to Barranco Camp via Lava Tower',
                        'destinations' => 'Shira Camp, Lava Tower (4,600m), Barranco Camp (3,960m)',
                        'meals' => 'Breakfast, Lunch, Dinner',
                        'description' => 'Climb high toward Lava Tower at about 4,600m for acclimatization, then descend into Barranco Camp at 3,960m. This climb high, sleep low day is important for summit success and usually takes six to eight hours.',
                    ],
                    [
                        'title' => 'Barranco Camp to Barafu Camp',
                        'destinations' => 'Barranco Camp, Karanga, Barafu Camp (4,670m)',
                        'meals' => 'Breakfast, Lunch, Dinner',
                        'description' => 'Start with the Barranco Wall scramble, continue to Karanga for lunch, then climb on to Barafu Camp at about 4,670m. Rest, early dinner, and prepare for the midnight summit departure.',
                    ],
                    [
                        'title' => 'Barafu Camp to Uhuru Peak to Mweka Camp',
                        'destinations' => 'Uhuru Peak (5,895m), Mweka Camp (3,100m)',
                        'meals' => 'Breakfast, Lunch, Dinner',
                        'description' => 'Leave Barafu around midnight, climb to Stella Point on the crater rim, and walk on to Uhuru Peak. After sunrise you descend past Barafu all the way to Mweka Camp at about 3,100m for the final night on the mountain.',
                    ],
                    [
                        'title' => 'Mweka Camp to Mweka Gate',
                        'destinations' => 'Mweka Camp, Mweka Gate',
                        'meals' => 'Breakfast, Lunch',
                        'description' => 'A steady rainforest descent to Mweka Gate takes three to four hours. Collect your certificate and transfer to Moshi or Arusha.',
                    ],
                ],
            ],
            '7 Days Machame Route, Kilimanjaro' => [
                'overview' => 'Seven days on Machame adds a night at Karanga Camp between Barranco and Barafu. The extra day eases the approach to summit camp and is the preferred Machame schedule for most climbers.',
                'includes' => $trekIncludes,
                'days' => [
                    [
                        'title' => 'Machame Gate to Machame Camp',
                        'destinations' => 'Machame Gate (1,800m), Machame Camp (3,000m)',
                        'meals' => 'Lunch, Dinner',
                        'description' => 'Trek from Machame Gate through rainforest to Machame Camp at 3,000m. Expect five to seven hours of walking and an overnight under canvas.',
                    ],
                    [
                        'title' => 'Machame Camp to Shira Camp',
                        'destinations' => 'Machame Camp, Shira Camp (3,840m)',
                        'meals' => 'Breakfast, Lunch, Dinner',
                        'description' => 'Ascend onto the Shira Plateau and camp at Shira Camp near 3,840m. The trail leaves the trees behind and opens toward Kibo.',
                    ],
                    [
                        'title' => 'Shira Camp to Barranco Camp via Lava Tower',
                        'destinations' => 'Shira Camp, Lava Tower (4,600m), Barranco Camp (3,960m)',
                        'meals' => 'Breakfast, Lunch, Dinner',
                        'description' => 'Hike up to Lava Tower for altitude exposure, then drop into Barranco Valley for overnight at 3,960m beneath the Barranco Wall.',
                    ],
                    [
                        'title' => 'Barranco Camp to Karanga Camp',
                        'destinations' => 'Barranco Camp, Karanga Camp (3,995m)',
                        'meals' => 'Breakfast, Lunch, Dinner',
                        'description' => 'Climb the Barranco Wall and traverse to Karanga Camp at about 3,995m. The day is shorter, which helps recovery before the final push to Barafu.',
                    ],
                    [
                        'title' => 'Karanga Camp to Barafu Camp',
                        'destinations' => 'Karanga Camp, Barafu Camp (4,670m)',
                        'meals' => 'Breakfast, Lunch, Dinner',
                        'description' => 'A steady climb to Barafu Camp, the staging point for summit night. Arrive early, eat, hydrate, and rest before the midnight departure.',
                    ],
                    [
                        'title' => 'Barafu Camp to Uhuru Peak to Mweka Camp',
                        'destinations' => 'Uhuru Peak (5,895m), Mweka Camp (3,100m)',
                        'meals' => 'Breakfast, Lunch, Dinner',
                        'description' => 'Summit night takes you to Stella Point and Uhuru Peak at 5,895m. Descend carefully to Mweka Camp for the last night on the mountain.',
                    ],
                    [
                        'title' => 'Mweka Camp to Mweka Gate',
                        'destinations' => 'Mweka Camp, Mweka Gate',
                        'meals' => 'Breakfast, Lunch',
                        'description' => 'Descend through rainforest to Mweka Gate, receive certificates, and transfer back to town.',
                    ],
                ],
            ],
            '6 Days Rongai Route, Kilimanjaro' => [
                'overview' => 'The Rongai Route approaches Kilimanjaro from the north near the Kenyan border. It is drier and quieter than the southern routes, camps along the way to Kibo Hut, and descends via Horombo to Marangu Gate.',
                'includes' => $trekIncludes,
                'days' => [
                    [
                        'title' => 'Rongai Gate to Simba Camp',
                        'destinations' => 'Rongai Gate (1,950m), Simba Camp (2,625m)',
                        'meals' => 'Lunch, Dinner',
                        'description' => 'Drive to Rongai Gate for registration, then trek through farmland and forest to Simba Camp at about 2,625m. The northern side is often less crowded than Machame or Marangu.',
                    ],
                    [
                        'title' => 'Simba Camp to Kikelewa Camp',
                        'destinations' => 'Simba Camp, Kikelewa Camp (3,600m)',
                        'meals' => 'Breakfast, Lunch, Dinner',
                        'description' => 'Continue through moorland toward Kikelewa Camp at around 3,600m. Views toward Mawenzi improve as you gain height on this quieter trail.',
                    ],
                    [
                        'title' => 'Kikelewa Camp to Mawenzi Tarn Camp',
                        'destinations' => 'Kikelewa Camp, Mawenzi Tarn (4,330m)',
                        'meals' => 'Breakfast, Lunch, Dinner',
                        'description' => 'A shorter but steeper day to Mawenzi Tarn Camp at 4,330m, set beneath the towers of Mawenzi Peak. The tarn setting is one of the most scenic camps on Kilimanjaro.',
                    ],
                    [
                        'title' => 'Mawenzi Tarn to Kibo Hut',
                        'destinations' => 'Mawenzi Tarn, Kibo Hut (4,700m)',
                        'meals' => 'Breakfast, Lunch, Dinner',
                        'description' => 'Cross the alpine desert saddle to Kibo Hut. Rest through the afternoon and prepare for summit night.',
                    ],
                    [
                        'title' => 'Kibo Hut to Uhuru Peak to Horombo Hut',
                        'destinations' => 'Uhuru Peak (5,895m), Horombo Hut',
                        'meals' => 'Breakfast, Lunch, Dinner',
                        'description' => 'Midnight ascent via Gilman’s Point to Uhuru Peak, then a long descent to Horombo Hut for overnight.',
                    ],
                    [
                        'title' => 'Horombo Hut to Marangu Gate',
                        'destinations' => 'Horombo Hut, Marangu Gate',
                        'meals' => 'Breakfast, Lunch',
                        'description' => 'Descend the Marangu trail to the gate, collect certificates, and transfer to Moshi or Arusha.',
                    ],
                ],
            ],
            '7 Days Rongai Route, Kilimanjaro' => [
                'overview' => 'Seven days on Rongai adds a Second Cave Camp night for better acclimatization on Kilimanjaro’s northern approach. You still summit from Kibo and exit through Marangu Gate.',
                'includes' => $trekIncludes,
                'days' => [
                    [
                        'title' => 'Rongai Gate to Simba Camp',
                        'destinations' => 'Rongai Gate (1,950m), Simba Camp (2,625m)',
                        'meals' => 'Lunch, Dinner',
                        'description' => 'Begin on the north side at Rongai Gate and walk to Simba Camp through forest and farmland edges.',
                    ],
                    [
                        'title' => 'Simba Camp to Second Cave Camp',
                        'destinations' => 'Simba Camp, Second Cave Camp (3,450m)',
                        'meals' => 'Breakfast, Lunch, Dinner',
                        'description' => 'Trek to Second Cave Camp at about 3,450m. The extra overnight here makes the seven day Rongai profile more comfortable than the six day version.',
                    ],
                    [
                        'title' => 'Second Cave Camp to Kikelewa Camp',
                        'destinations' => 'Second Cave Camp, Kikelewa Camp (3,600m)',
                        'meals' => 'Breakfast, Lunch, Dinner',
                        'description' => 'Continue across moorland to Kikelewa Camp, keeping a slow pace as altitude increases.',
                    ],
                    [
                        'title' => 'Kikelewa Camp to Mawenzi Tarn Camp',
                        'destinations' => 'Kikelewa Camp, Mawenzi Tarn (4,330m)',
                        'meals' => 'Breakfast, Lunch, Dinner',
                        'description' => 'Climb to Mawenzi Tarn Camp beneath Mawenzi Peak. Short afternoon walks around camp help acclimatization.',
                    ],
                    [
                        'title' => 'Mawenzi Tarn to Kibo Hut',
                        'destinations' => 'Mawenzi Tarn, Kibo Hut (4,700m)',
                        'meals' => 'Breakfast, Lunch, Dinner',
                        'description' => 'Traverse the saddle to Kibo Hut, rest, and prepare for the midnight summit attempt.',
                    ],
                    [
                        'title' => 'Kibo Hut to Uhuru Peak to Horombo Hut',
                        'destinations' => 'Uhuru Peak (5,895m), Horombo Hut',
                        'meals' => 'Breakfast, Lunch, Dinner',
                        'description' => 'Summit via Gilman’s Point to Uhuru Peak, then descend to Horombo Hut for overnight recovery.',
                    ],
                    [
                        'title' => 'Horombo Hut to Marangu Gate',
                        'destinations' => 'Horombo Hut, Marangu Gate',
                        'meals' => 'Breakfast, Lunch',
                        'description' => 'Walk down to Marangu Gate for certificates and your return transfer.',
                    ],
                ],
            ],
            '6 Days Umbwe Route, Kilimanjaro' => [
                'overview' => 'Umbwe is the steepest and most direct southern route on Kilimanjaro. It joins the Machame circuit at Barranco and suits fit trekkers who want a short, demanding line to Barafu and Uhuru Peak.',
                'includes' => $trekIncludes,
                'days' => [
                    [
                        'title' => 'Umbwe Gate to Umbwe Cave Camp',
                        'destinations' => 'Umbwe Gate (1,600m), Umbwe Cave Camp (2,850m)',
                        'meals' => 'Lunch, Dinner',
                        'description' => 'From Umbwe Gate the trail rises quickly along a forested ridge to Umbwe Cave Camp at about 2,850m. The day is shorter in hours than Machame but steeper underfoot.',
                    ],
                    [
                        'title' => 'Umbwe Cave Camp to Barranco Camp',
                        'destinations' => 'Umbwe Cave Camp, Barranco Camp (3,960m)',
                        'meals' => 'Breakfast, Lunch, Dinner',
                        'description' => 'Continue up the ridge as forest gives way to giant heather, then drop into Barranco Camp. You join the southern circuit routes here beneath the Barranco Wall.',
                    ],
                    [
                        'title' => 'Barranco Camp to Karanga Camp',
                        'destinations' => 'Barranco Camp, Karanga Camp (3,995m)',
                        'meals' => 'Breakfast, Lunch, Dinner',
                        'description' => 'Climb the Barranco Wall and traverse to Karanga Camp. The scramble is exposed in places but non technical with guide support.',
                    ],
                    [
                        'title' => 'Karanga Camp to Barafu Camp',
                        'destinations' => 'Karanga Camp, Barafu Camp (4,670m)',
                        'meals' => 'Breakfast, Lunch, Dinner',
                        'description' => 'Ascend to Barafu Camp, rest, and prepare gear for summit night.',
                    ],
                    [
                        'title' => 'Barafu Camp to Uhuru Peak to Mweka Camp',
                        'destinations' => 'Uhuru Peak (5,895m), Mweka Camp (3,100m)',
                        'meals' => 'Breakfast, Lunch, Dinner',
                        'description' => 'Midnight climb to Stella Point and Uhuru Peak, then a long descent to Mweka Camp.',
                    ],
                    [
                        'title' => 'Mweka Camp to Mweka Gate',
                        'destinations' => 'Mweka Camp, Mweka Gate',
                        'meals' => 'Breakfast, Lunch',
                        'description' => 'Final rainforest walk to Mweka Gate for certificates and transfer to town.',
                    ],
                ],
            ],
            '7 Days Lemosho Route, Kilimanjaro' => [
                'overview' => 'Seven day Lemosho starts on Kilimanjaro’s quiet western flank, crosses the Shira Plateau, and joins the southern circuit at Barranco. It balances scenery and acclimatization, with camping throughout.',
                'includes' => $trekIncludes,
                'days' => [
                    [
                        'title' => 'Londorossi Gate to Mti Mkubwa Camp',
                        'destinations' => 'Londorossi Gate (2,100m), Mti Mkubwa Camp (2,650m)',
                        'meals' => 'Lunch, Dinner',
                        'description' => 'Drive to Londorossi Gate for registration, then short transfer to the Lemosho trailhead. Trek through rainforest to Mti Mkubwa (Big Tree) Camp at about 2,650m.',
                    ],
                    [
                        'title' => 'Mti Mkubwa Camp to Shira 1 Camp',
                        'destinations' => 'Mti Mkubwa Camp, Shira 1 Camp (3,610m)',
                        'meals' => 'Breakfast, Lunch, Dinner',
                        'description' => 'Climb out of the forest onto the Shira Ridge and continue to Shira 1 Camp at 3,610m with wide plateau views.',
                    ],
                    [
                        'title' => 'Shira 1 Camp to Shira 2 Camp',
                        'destinations' => 'Shira 1 Camp, Shira 2 Camp (3,850m)',
                        'meals' => 'Breakfast, Lunch, Dinner',
                        'description' => 'A shorter traverse across the Shira Plateau to Shira 2 Camp at 3,850m. Use the afternoon to rest and enjoy the open landscape.',
                    ],
                    [
                        'title' => 'Shira 2 Camp to Barranco Camp via Lava Tower',
                        'destinations' => 'Shira 2 Camp, Lava Tower (4,600m), Barranco Camp (3,960m)',
                        'meals' => 'Breakfast, Lunch, Dinner',
                        'description' => 'Climb to Lava Tower for acclimatization, then descend to Barranco Camp. This is a key climb high, sleep low day on Lemosho.',
                    ],
                    [
                        'title' => 'Barranco Camp to Barafu Camp via Karanga',
                        'destinations' => 'Barranco Camp, Karanga, Barafu Camp (4,670m)',
                        'meals' => 'Breakfast, Lunch, Dinner',
                        'description' => 'Cross the Barranco Wall, stop at Karanga for lunch, and continue to Barafu Camp for summit preparation.',
                    ],
                    [
                        'title' => 'Barafu Camp to Uhuru Peak to Mweka Camp',
                        'destinations' => 'Uhuru Peak (5,895m), Mweka Camp (3,100m)',
                        'meals' => 'Breakfast, Lunch, Dinner',
                        'description' => 'Summit from Barafu to Uhuru Peak via Stella Point, then descend to Mweka Camp.',
                    ],
                    [
                        'title' => 'Mweka Camp to Mweka Gate',
                        'destinations' => 'Mweka Camp, Mweka Gate',
                        'meals' => 'Breakfast, Lunch',
                        'description' => 'Descend to Mweka Gate, receive certificates, and transfer to Moshi or Arusha.',
                    ],
                ],
            ],
            '8 Days Lemosho Route, Kilimanjaro' => [
                'overview' => 'Eight day Lemosho is one of the strongest summit schedules on Kilimanjaro. Separate nights at Karanga and Barafu give more recovery before summit night after the western approach across Shira.',
                'includes' => $trekIncludes,
                'days' => [
                    [
                        'title' => 'Londorossi Gate to Mti Mkubwa Camp',
                        'destinations' => 'Londorossi Gate (2,100m), Mti Mkubwa Camp (2,650m)',
                        'meals' => 'Lunch, Dinner',
                        'description' => 'Register at Londorossi Gate and trek to Mti Mkubwa Camp through western rainforest.',
                    ],
                    [
                        'title' => 'Mti Mkubwa Camp to Shira 1 Camp',
                        'destinations' => 'Mti Mkubwa Camp, Shira 1 Camp (3,610m)',
                        'meals' => 'Breakfast, Lunch, Dinner',
                        'description' => 'Ascend to the Shira Plateau and camp at Shira 1 with views back over the western forests.',
                    ],
                    [
                        'title' => 'Shira 1 Camp to Shira 2 Camp',
                        'destinations' => 'Shira 1 Camp, Shira 2 Camp (3,850m)',
                        'meals' => 'Breakfast, Lunch, Dinner',
                        'description' => 'Cross the plateau to Shira 2 Camp. The shorter day helps acclimatization before Lava Tower.',
                    ],
                    [
                        'title' => 'Shira 2 Camp to Barranco Camp via Lava Tower',
                        'destinations' => 'Shira 2 Camp, Lava Tower (4,600m), Barranco Camp (3,960m)',
                        'meals' => 'Breakfast, Lunch, Dinner',
                        'description' => 'Climb to Lava Tower, then descend into Barranco Camp for overnight beneath the wall.',
                    ],
                    [
                        'title' => 'Barranco Camp to Karanga Camp',
                        'destinations' => 'Barranco Camp, Karanga Camp (3,995m)',
                        'meals' => 'Breakfast, Lunch, Dinner',
                        'description' => 'Scramble the Barranco Wall and continue to Karanga Camp for a shorter acclimatization overnight.',
                    ],
                    [
                        'title' => 'Karanga Camp to Barafu Camp',
                        'destinations' => 'Karanga Camp, Barafu Camp (4,670m)',
                        'meals' => 'Breakfast, Lunch, Dinner',
                        'description' => 'Climb to Barafu Camp, rest, and prepare for the midnight summit push.',
                    ],
                    [
                        'title' => 'Barafu Camp to Uhuru Peak to Mweka Camp',
                        'destinations' => 'Uhuru Peak (5,895m), Mweka Camp (3,100m)',
                        'meals' => 'Breakfast, Lunch, Dinner',
                        'description' => 'Summit to Uhuru Peak via Stella Point, then descend to Mweka Camp.',
                    ],
                    [
                        'title' => 'Mweka Camp to Mweka Gate',
                        'destinations' => 'Mweka Camp, Mweka Gate',
                        'meals' => 'Breakfast, Lunch',
                        'description' => 'Walk down to Mweka Gate for certificates and transfer to town.',
                    ],
                ],
            ],
            '9 Days Northern Circuit, Kilimanjaro' => [
                'overview' => 'The Northern Circuit is Kilimanjaro’s longest standard camping route. Starting via Lemosho, it swings north around Kibo through Moir, Buffalo, and Third Cave camps before summiting from School Hut and descending via Mweka.',
                'includes' => $trekIncludes,
                'days' => [
                    [
                        'title' => 'Londorossi Gate to Mti Mkubwa Camp',
                        'destinations' => 'Londorossi Gate (2,100m), Mti Mkubwa Camp (2,650m)',
                        'meals' => 'Lunch, Dinner',
                        'description' => 'Begin like Lemosho with registration at Londorossi and a rainforest walk to Mti Mkubwa Camp.',
                    ],
                    [
                        'title' => 'Mti Mkubwa Camp to Shira 1 Camp',
                        'destinations' => 'Mti Mkubwa Camp, Shira 1 Camp (3,610m)',
                        'meals' => 'Breakfast, Lunch, Dinner',
                        'description' => 'Climb onto the Shira Plateau and camp at Shira 1.',
                    ],
                    [
                        'title' => 'Shira 1 Camp to Shira 2 Camp',
                        'destinations' => 'Shira 1 Camp, Shira 2 Camp (3,850m)',
                        'meals' => 'Breakfast, Lunch, Dinner',
                        'description' => 'Traverse to Shira 2 Camp across open plateau terrain with growing views of Kibo.',
                    ],
                    [
                        'title' => 'Shira 2 Camp to Moir Hut',
                        'destinations' => 'Shira 2 Camp, Moir Hut (4,200m)',
                        'meals' => 'Breakfast, Lunch, Dinner',
                        'description' => 'Leave the southern circuit and head north to Moir Hut at about 4,200m. An optional afternoon hike up the nearby ridge helps acclimatization.',
                    ],
                    [
                        'title' => 'Moir Hut to Buffalo Camp',
                        'destinations' => 'Moir Hut, Buffalo Camp (4,150m)',
                        'meals' => 'Breakfast, Lunch, Dinner',
                        'description' => 'Contour around the northern slopes to Buffalo Camp. This quieter section is the heart of the Northern Circuit and offers wide views toward Kenya on clear days.',
                    ],
                    [
                        'title' => 'Buffalo Camp to Third Cave Camp',
                        'destinations' => 'Buffalo Camp, Third Cave Camp (3,800m)',
                        'meals' => 'Breakfast, Lunch, Dinner',
                        'description' => 'Continue east to Third Cave Camp. The day is moderate and keeps you circling Kibo before the final approach.',
                    ],
                    [
                        'title' => 'Third Cave Camp to School Hut',
                        'destinations' => 'Third Cave Camp, School Hut (4,800m)',
                        'meals' => 'Breakfast, Lunch, Dinner',
                        'description' => 'Climb to School Hut at about 4,800m, the Northern Circuit summit camp. Rest early and prepare for a midnight start.',
                    ],
                    [
                        'title' => 'School Hut to Uhuru Peak to Mweka Camp',
                        'destinations' => 'Uhuru Peak (5,895m), Mweka Camp (3,100m)',
                        'meals' => 'Breakfast, Lunch, Dinner',
                        'description' => 'Ascend to the crater rim and Uhuru Peak, then make the long descent to Mweka Camp on the south side.',
                    ],
                    [
                        'title' => 'Mweka Camp to Mweka Gate',
                        'destinations' => 'Mweka Camp, Mweka Gate',
                        'meals' => 'Breakfast, Lunch',
                        'description' => 'Final descent through rainforest to Mweka Gate for certificates and transfer home.',
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array<string, array{overview: string, days: list<array{title: string, description: string, meals?: string, destinations?: string}>, includes?: list<string>}>
     */
    private function dayTripDefinitions(): array
    {
        $dayIncludes = [
            'Park or attraction fees where applicable',
            'Professional guide',
            'Private transport from Arusha or Moshi',
            'Bottled drinking water',
            'Picnic lunch on full day outings',
        ];

        return [
            'Arusha National Park Day Safari' => [
                'overview' => 'A full day in Arusha National Park beneath Mount Meru. The park is close to town and offers walking safari options, canoeing on Momella Lakes when available, and game drives among giraffe, buffalo, colobus monkey, and rich birdlife.',
                'includes' => $dayIncludes,
                'days' => [
                    [
                        'title' => 'Full day in Arusha National Park',
                        'destinations' => 'Arusha National Park',
                        'meals' => 'Lunch',
                        'description' => 'Morning pickup from your hotel in Arusha or Moshi and drive to Arusha National Park. Game drive through fig forest and open glades toward the Momella Lakes and Ngurdoto Crater viewpoints. Where booked in advance, a guided walking safari or canoe trip can be added. Picnic lunch is taken inside the park before an afternoon drive and return to your hotel by late afternoon.',
                    ],
                ],
            ],
            'Ngorongoro Crater 1-Day Safari' => [
                'overview' => 'A one day safari into Ngorongoro Crater from Arusha. You descend onto the crater floor for concentrated Big Five viewing and return the same evening.',
                'includes' => $dayIncludes,
                'days' => [
                    [
                        'title' => 'Ngorongoro Crater day trip from Arusha',
                        'destinations' => 'Ngorongoro Crater',
                        'meals' => 'Lunch',
                        'description' => 'Early departure from Arusha to the Ngorongoro Conservation Area. After rim registration you drive down to the crater floor for a long morning game drive among lion, buffalo, elephant, hippo, and often black rhino. Picnic lunch is served near the hippo pool. In the afternoon you climb out of the crater and return to Arusha.',
                    ],
                ],
            ],
            'Tarangire National Park Day Safari' => [
                'overview' => 'A day safari in Tarangire National Park, known for elephant herds, baobab landscapes, and river wildlife within easy reach of Arusha.',
                'includes' => $dayIncludes,
                'days' => [
                    [
                        'title' => 'Full day game drive in Tarangire',
                        'destinations' => 'Tarangire National Park',
                        'meals' => 'Lunch',
                        'description' => 'Leave Arusha after breakfast for the roughly two hour drive to Tarangire National Park. Spend the day on game drives along the Tarangire River and through baobab woodland looking for elephant, lion, giraffe, zebra, and many bird species. Picnic lunch is taken at a park site before the afternoon drive and return to Arusha.',
                    ],
                ],
            ],
            'Materuni Waterfalls and Coffee Tour, Moshi' => [
                'overview' => 'A cultural day trip from Moshi to Materuni village on the lower slopes of Kilimanjaro. Visit a local waterfall and join a Chagga coffee demonstration from picking to cup.',
                'includes' => [
                    'Village and waterfall visit fees',
                    'Local coffee tour guide',
                    'Private transport from Moshi',
                    'Traditional lunch in the village',
                    'Bottled drinking water',
                ],
                'days' => [
                    [
                        'title' => 'Materuni waterfall and coffee tour',
                        'destinations' => 'Materuni Village, Moshi',
                        'meals' => 'Lunch',
                        'description' => 'Drive from Moshi into the banana and coffee farms of Materuni. Walk with a local guide to the waterfall, swim if you wish, then return to the village for a hands on coffee tour covering roasting, grinding, and brewing. A traditional Chagga lunch is served before your transfer back to Moshi.',
                    ],
                ],
            ],
            'Kikuletwa (Chemka) Hot Springs Day Trip' => [
                'overview' => 'A relaxed day at the Kikuletwa Hot Springs, also called Chemka, where clear spring water wells up under mahogany trees southwest of Moshi.',
                'includes' => [
                    'Entrance fees',
                    'Private transport from Moshi or Arusha',
                    'Bottled drinking water',
                    'Picnic lunch',
                ],
                'days' => [
                    [
                        'title' => 'Kikuletwa Hot Springs day trip',
                        'destinations' => 'Kikuletwa Hot Springs (Chemka)',
                        'meals' => 'Lunch',
                        'description' => 'Pickup from Moshi or Arusha and drive to the Kikuletwa Hot Springs. Spend the day swimming in the clear spring pools, relaxing under the trees, and enjoying a picnic lunch. Return to your hotel in the afternoon.',
                    ],
                ],
            ],
        ];
    }

    /**
     * @return list<string>
     */
    private function defaultIncludes(string $category): array
    {
        return match ($category) {
            'Trekking' => [
                'Park fees and rescue fees',
                'Mountain guide, cook, and porters',
                'Camping or hut accommodation as per route',
                'Meals on the mountain',
            ],
            'Day Trip', 'Cultural' => [
                'Park or attraction fees where applicable',
                'Professional guide',
                'Private transport',
                'Bottled drinking water',
            ],
            default => [
                'Park entry fees',
                'Safari guide and 4x4 game drive vehicle',
                'Accommodation as per itinerary',
                'Meals as outlined for the tour',
            ],
        };
    }

    /**
     * @return list<string>
     */
    private function defaultExcludes(): array
    {
        return [
            'International airfare',
            'Tanzania visa fees',
            'Travel insurance',
            'Tips for guides, porters, and camp staff',
            'Personal gear and souvenirs',
            'Alcoholic drinks and items of a personal nature',
        ];
    }
}
