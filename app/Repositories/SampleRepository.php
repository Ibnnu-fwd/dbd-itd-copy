<?php

namespace App\Repositories;

use App\Models\DetailSampleMorphotype;
use App\Models\DetailSampleVirus;
use App\Models\District;
use App\Models\Morphotype;
use App\Models\Province;
use App\Models\Regency;
use App\Models\Sample;
// use App\Models\SampleMethod;
use App\Models\Village;
use App\Models\Virus;
use App\Repositories\Interface\SampleInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SampleRepository implements SampleInterface
{
    private $sample;
    // private $sampleMethod;
    private $province;
    private $regency;
    private $district;
    private $village;
    private $detailSampleVirus;
    private $detailSampleMorophotype;

    public function __construct(
        Sample $sample,
        // SampleMethod $sampleMethod,
        Province $province,
        Regency $regency,
        District $district,
        Village $village,
        DetailSampleVirus $detailSampleVirus,
        DetailSampleMorphotype $detailSampleMorphotype
    ) {
        $this->sample                     = $sample;
        // $this->sampleMethod               = $sampleMethod;
        $this->province                   = $province;
        $this->regency                    = $regency;
        $this->district                   = $district;
        $this->village                    = $village;
        $this->detailSampleVirus          = $detailSampleVirus;
        $this->detailSampleMorophotype    = $detailSampleMorphotype;
    }

    public function getAll()
    {
        $samples = $this->sample->with('province', 'regency', 'district', 'village', 'createdBy', 'updatedBy', 'detailSampleViruses', 'detailSampleViruses.virus', 'detailSampleViruses.detailSampleMorphotypes', 'detailSampleViruses.detailSampleMorphotypes.detailSampleSerotypes')->active()->get();

        $samples = $samples->map(function ($item) {
            $item['total_sample'] = $item->detailSampleViruses->map(function ($item) {
                $amount = 0;
                $item->detailSampleMorphotypes->map(function ($item) use (&$amount) {
                    $amount += $item->detailSampleSerotypes->map(function ($item) {
                        return $item->amount;
                    })->sum();
                });
                return $amount;
            })->sum();
            return $item;
        });

        return $samples;
    }

    public function getById($id)
    {
        return $this->sample->with('province', 'regency', 'district', 'village')->active()->find($id);
    }

    public function create(array $attributes)
    {
        DB::beginTransaction();
        try {
            $sample = $this->sample->create([
                'sample_code' => $this->sample->generateSampleCode(),
                // 'sample_method_id' => $attributes['sample_method_id'],
                'latitude' => $attributes['latitude'],
                'longitude' => $attributes['longitude'],
                'province_id' => $attributes['province_id'],
                'regency_id' => $attributes['regency_id'],
                'district_id' => $attributes['district_id'],
                'village_id' => $attributes['village_id'],
                'public_health_name' => $attributes['public_health_name'] ?? null,
                'location_name' => $attributes['location_name'] ?? null,
                'location_type_id' => $attributes['location_type_id'] ?? null,
                'description' => $attributes['description'] ?? null,
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        try {
            foreach ($attributes['viruses'] as $virus => $key) {
                $this->detailSampleVirus->create([
                    'sample_id' => $sample->id,
                    'virus_id' => $key,
                ]);
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        DB::commit();
    }

    public function update($id, array $attributes)
    {
        DB::beginTransaction();
        try {
            $sample = $this->sample->find($id)->update([
                // 'sample_method_id' => $attributes['sample_method_id'],
                'public_health_name' => $attributes['public_health_name'],
                'location_name' => $attributes['location_name'],
                'location_type_id' => $attributes['location_type_id'],
                'description' => $attributes['description'] ?? null,
                'province_id' => $attributes['province_id'],
                'regency_id' => $attributes['regency_id'],
                'district_id' => $attributes['district_id'],
                'village_id' => $attributes['village_id'],
                'latitude' => $attributes['latitude'],
                'longitude' => $attributes['longitude'],
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        if (isset($attributes['viruses'])) {
            try {
                foreach ($attributes['viruses'] as $virus => $key) {
                    $this->detailSampleVirus->create([
                        'sample_id' => $id,
                        'virus_id' => $key,
                    ]);
                }
            } catch (\Throwable $th) {
                DB::rollBack();
                throw $th;
            }
        }

        DB::commit();
    }

    public function delete($id)
    {
        return $this->sample->find($id)->update([
            'is_active' => false,
        ]);
    }

    public function detailSample($id)
    {
        return $this->sample->active()->with('detailSampleViruses', 'detailSampleViruses.detailSampleMorphotypes', 'detailSampleViruses.detailSampleMorphotypes.detailSampleSerotypes')->find($id);
    }

    public function getAllRegency()
    {
        $sample = $this->sample->active()->with('detailSampleViruses', 'detailSampleViruses.virus', 'detailSampleViruses.detailSampleMorphotypes')->get();

        $data = [];
        foreach ($sample as $key => $value) {
            $data[$key]['regency_id'] = $value->regency_id;
            $data[$key]['regency'] = $value->regency->name;
            $data[$key]['location'] = $value->latitude . ', ' . $value->longitude;
            $data[$key]['count'] = $value->detailSampleViruses->map(function ($item) {
                $amount = 0;
                $item->detailSampleMorphotypes->map(function ($item) use (&$amount) {
                    // sum serotype amount
                    $amount += $item->detailSampleSerotypes->map(function ($item) {
                        return $item->amount;
                    })->sum();
                });
                return $amount;
            })->sum();
            $data[$key]['type'] = $value->detailSampleViruses->map(function ($item) {
                return [
                    'name' => $item->virus->name,
                    'amount' => $item->detailSampleMorphotypes->map(function ($item) {
                        // sum serotype amount
                        return $item->detailSampleSerotypes->map(function ($item) {
                            return $item->amount;
                        })->sum();
                    })->sum(),
                ];
            });
        }

        // sum amount of same regency by index
        $data = collect($data)->groupBy('regency')->map(function ($item) {
            $amount = 0;
            $type = [];
            foreach ($item as $key => $value) {
                $amount += $value['count'];
                foreach ($value['type'] as $key => $value) {
                    if (isset($type[$value['name']])) {
                        $type[$value['name']] += $value['amount'];
                    } else {
                        $type[$value['name']] = $value['amount'];
                    }
                }
            }
            return [
                'regency_id' => $item[0]['regency_id'],
                'regency' => $item[0]['regency'],
                'location' => $item[0]['location'],
                'count' => $amount,
                'type' => $type,
            ];
        });

        // change index to number
        $data = $data->values();

        $data = $data->map(function ($item) {
            $item['type'] = collect($item['type'])->map(function ($item, $key) {
                return [
                    'name' => $key,
                    'amount' => $item,
                ];
            });
            return $item;
        });


        return $data;
    }

    public function getAllGroupByDistrict($regency_id)
    {
        $sample = $this->sample->active()->with('detailSampleViruses', 'detailSampleViruses.virus', 'detailSampleViruses.detailSampleMorphotypes')->where('regency_id', $regency_id)->get();

        $data = [];
        foreach ($sample as $key => $value) {
            $data[$key]['district_id'] = $value->district_id;
            $data[$key]['district'] = $value->district->name;
            $data[$key]['regency'] = $value->regency->name;
            $data[$key]['latitude'] = $value->latitude;
            $data[$key]['longitude'] = $value->longitude;
            $data[$key]['count'] = $value->detailSampleViruses->map(function ($item) {
                $amount = 0;
                $item->detailSampleMorphotypes->map(function ($item) use (&$amount) {
                    // sum serotype amount
                    $amount += $item->detailSampleSerotypes->map(function ($item) {
                        return $item->amount;
                    })->sum();
                });
                return $amount;
            })->sum();
            $data[$key]['type'] = $value->detailSampleViruses->map(function ($item) {
                return [
                    'name' => $item->virus->name,
                    // sum amount of same morphotype and even thought there're more than
                    'amount' => $item->detailSampleMorphotypes->map(function ($item) {
                        return $item->detailSampleSerotypes->map(function ($item) {
                            return $item->amount;
                        })->sum();
                        return $item->amount;
                    })->sum(),
                ];
            });
            $data[$key]['created_at'] = $value->created_at->format('Y-m-d');
        }

        return $data;
    }

    public function getAllGroupByDistrictFilterByMonth($regency_id, $month)
    {
        $sample = $this->sample->active()->with('detailSampleViruses', 'detailSampleViruses.virus', 'detailSampleViruses.detailSampleMorphotypes')->where([
            ['regency_id', $regency_id],
            [DB::raw('MONTH(created_at)'), $month],
        ])->get();

        // dd($sample);

        $data = [];
        foreach ($sample as $key => $value) {
            $data[$key]['district_id'] = $value->district_id;
            $data[$key]['district'] = $value->district->name;
            $data[$key]['regency'] = $value->regency->name;
            $data[$key]['latitude'] = $value->latitude;
            $data[$key]['longitude'] = $value->longitude;
            $data[$key]['count'] = $value->detailSampleViruses->map(function ($item) {
                $amount = 0;
                $item->detailSampleMorphotypes->map(function ($item) use (&$amount) {
                    $amount += $item->detailSampleSerotypes->map(function ($item) {
                        return $item->amount;
                    })->sum();
                });
                return $amount;
            })->sum();
            $data[$key]['type'] = $value->detailSampleViruses->map(function ($item) {
                return [
                    'name' => $item->virus->name,
                    'amount' => $item->detailSampleMorphotypes->map(function ($item) {
                        // sum serotype amount
                        return $item->detailSampleSerotypes->map(function ($item) {
                            return $item->amount;
                        })->sum();
                    })->sum(),
                ];
            });
            $data[$key]['created_at'] = $value->created_at->format('Y-m-d');
        }

        return collect($data);
    }

    public function getAllGroupByDistrictFilterByDateRange($regency_id, $start_date, $end_date)
    {
        $start_date = date('Y-m-d', strtotime($start_date));
        $end_date = date('Y-m-d', strtotime($end_date));
        $sample = $this->sample->active()->with('detailSampleViruses', 'detailSampleViruses.virus', 'detailSampleViruses.detailSampleMorphotypes')->where([
            ['regency_id', $regency_id],
            ['created_at', '>=', $start_date],
            ['created_at', '<=', $end_date],
        ])->get();

        $data = [];
        foreach ($sample as $key => $value) {
            $data[$key]['district_id'] = $value->district_id;
            $data[$key]['district'] = $value->district->name;
            $data[$key]['regency'] = $value->regency->name;
            $data[$key]['latitude'] = $value->latitude;
            $data[$key]['longitude'] = $value->longitude;
            $data[$key]['count'] = $value->detailSampleViruses->map(function ($item) {
                $amount = 0;
                $item->detailSampleMorphotypes->map(function ($item) use (&$amount) {
                    $amount += $item->detailSampleSerotypes->map(function ($item) {
                        return $item->amount;
                    })->sum();
                });
                return $amount;
            })->sum();
            $data[$key]['type'] = $value->detailSampleViruses->map(function ($item) {
                return [
                    'name' => $item->virus->name,
                    'amount' => $item->detailSampleMorphotypes->map(function ($item) {
                        return $item->amount;
                    })->sum(),
                ];
            });
            $data[$key]['created_at'] = $value->created_at->format('Y-m-d');
        }

        return collect($data);
    }

    public function getSamplePerYear($year = null)
    {
        $sample = $this->sample->active()->with('detailSampleViruses', 'detailSampleViruses.virus', 'detailSampleViruses.detailSampleMorphotypes')->whereYear('created_at', $year)->get();

        // get sample per month in a year, sum amount of same month, sum each virus in a month, keep enter virus type even the amount is 0
        $data = [];
        foreach ($sample as $key => $value) {
            $data[$key]['month'] = $value->created_at->format('m');
            $data[$key]['count'] = $value->detailSampleViruses->map(function ($item) {
                $amount = 0;
                $item->detailSampleMorphotypes->map(function ($item) use (&$amount) {
                    $amount += $item->detailSampleSerotypes->map(function ($item) {
                        return $item->amount;
                    })->sum();
                });
                return $amount;
            })->sum();
            $data[$key]['type'] = $value->detailSampleViruses->map(function ($item) {
                return [
                    'name' => $item->virus->name,
                    'amount' => $item->detailSampleMorphotypes->map(function ($item) {
                        return $item->amount;
                    })->sum(),
                ];
            });
        }

        // add virus type even the amount is 0
        $virus = Virus::all();
        foreach ($virus as $key => $value) {
            $data = collect($data)->map(function ($item) use ($value) {
                // check if virus type is already exist, enter another virus type
                if ($item['type']->contains('name', $value->name)) {
                    return $item;
                } else {
                    $item['type']->push([
                        'name' => $value->name,
                        'amount' => 0,
                    ]);
                    return $item;
                }
            });
        }

        // sum amount of same month by index
        $data = collect($data)->groupBy('month')->map(function ($item) {
            $amount = 0;
            foreach ($item as $key => $value) {
                $amount += $value['count'];
            }
            return [
                'month' => $item[0]['month'],
                'count' => $amount,
                'type' => $item[0]['type'],
            ];
        });

        // change index to number
        $data = $data->values();

        // change month number to month name
        $data = $data->map(function ($item) {
            $item['month'] = Carbon::createFromFormat('m', $item['month'])->locale('id')->monthName;
            return $item;
        });

        return $data;
    }

    public function getTotalSample()
    {
        return $this->sample->active()->count();
    }

    public function getTotalMosquito()
    {
        $sample = $this->sample->active()->with('detailSampleViruses', 'detailSampleViruses.virus', 'detailSampleViruses.detailSampleMorphotypes')->get();

        $data = [];
        foreach ($sample as $key => $value) {
            $data[$key]['count'] = $value->detailSampleViruses->map(function ($item) {
                $amount = 0;
                $item->detailSampleMorphotypes->map(function ($item) use (&$amount) {
                    $amount += $item->detailSampleSerotypes->map(function ($item) {
                        return $item->amount;
                    })->sum();
                });
                return $amount;
            })->sum();
        }

        return collect($data)->sum('count');
    }

    public function getAllForUser($year = null, $regency_id = null)
    {
        $samples = $this->sample->with('province', 'regency', 'district', 'village', 'createdBy', 'updatedBy', 'detailSampleViruses', 'detailSampleViruses.virus', 'detailSampleViruses.detailSampleMorphotypes', 'detailSampleViruses.detailSampleMorphotypes.detailSampleSerotypes')->active()
        ->when($year, function ($query, $year) {
            return $query->whereYear('created_at', $year);
        })
        ->when($regency_id, function ($query, $regency_id) {
            return $query->whereHas('regency', function ($query) use ($regency_id) {
                return $query->where('id', $regency_id);
            });
        })
        ->get();

        $data = [];
        foreach ($samples as $sample) {
            $data[] = [
                // 'sample_code' => $sample->sample_code,
                'public_health_name' => ucwords(strtolower($sample->public_health_name)),
                // 'sample_method' => $sample->sampleMethod->name,
                'latitude' => $sample->latitude,
                'longitude' => $sample->longitude,
                'province' => ucwords(strtolower($sample->province->name)),
                'regency' => ucwords(strtolower($sample->regency->name)),
                'district' => ucwords(strtolower($sample->district->name)),
                'location_name' => ucwords(strtolower($sample->location_name)),
                'created_by' => ucwords(strtolower($sample->createdBy->name)),
                'created_at' => Carbon::parse($sample->created_at)->isoFormat('D MMMM Y'),
                'count' => $sample->detailSampleViruses->map(function ($item) {
                    $amount = 0;
                    $item->detailSampleMorphotypes->map(function ($item) use (&$amount) {
                        $amount += $item->detailSampleSerotypes->map(function ($item) {
                            return $item->amount;
                        })->sum();
                    });
                    return $amount;
                })->sum(),
                'type' => $sample->detailSampleViruses->map(function ($item) {
                    return [
                        'name' => $item->virus->name,
                        'amount' => $item->detailSampleMorphotypes->map(function ($item) {
                            return $item->amount;
                        })->sum(),
                    ];
                }),
            ];
        }

        return collect($data);
    }

    public function getHighestSampleInDistrictPerYear($year = null)
    {
        $sample = $this->sample->active()->with('detailSampleViruses', 'detailSampleViruses.virus', 'detailSampleViruses.detailSampleMorphotypes')->whereYear('created_at', $year)->get();

        $data = [];
        foreach ($sample as $key => $value) {
            $data[$key]['district'] = $value->district->name;
            $data[$key]['regency'] = $value->regency->name;
            $data[$key]['count'] = $value->detailSampleViruses->map(function ($item) {
                $amount = 0;
                $item->detailSampleMorphotypes->map(function ($item) use (&$amount) {
                    $amount += $item->detailSampleSerotypes->map(function ($item) {
                        return $item->amount;
                    })->sum();
                });
                return $amount;
            })->sum();
            $data[$key]['type'] = $value->detailSampleViruses->map(function ($item) {
                return [
                    'name' => $item->virus->name,
                    'amount' => $item->detailSampleMorphotypes->map(function ($item) {
                        return $item->amount;
                    })->sum(),
                ];
            });
            $data[$key]['created_at'] = $value->created_at->format('Y-m-d');
        }

        // sum amount of same district by index
        $data = collect($data)->groupBy('district')->map(function ($item) {
            $amount = 0;
            foreach ($item as $key => $value) {
                $amount += $value['count'];
            }
            return [
                'district' => ucwords(strtolower($item[0]['district'])),
                'regency' => $item[0]['regency'],
                'count' => $amount,
                'type' => $item[0]['type'],
            ];
        });

        // change index to number
        $data = $data->values();

        // sort by count of sample
        $data = $data->sortByDesc('count');

        // get top 10
        $data = $data->take(20);

        return $data;
    }

    public function getAllSampleByRegency($regency_id)
    {
        $sample = $this->sample->active()->with('detailSampleViruses', 'detailSampleViruses.virus', 'detailSampleViruses.detailSampleMorphotypes')->where('regency_id', $regency_id)->get();

        $data = [];
        foreach ($sample as $key => $value) {
            $data[$key]['district'] = $value->district->name;
            $data[$key]['regency'] = $value->regency->name;
            $data[$key]['count'] = $value->detailSampleViruses->map(function ($item) {
                $amount = 0;
                $item->detailSampleMorphotypes->map(function ($item) use (&$amount) {
                    $amount += $item->detailSampleSerotypes->map(function ($item) {
                        return $item->amount;
                    })->sum();
                });
                return $amount;
            })->sum();
            $data[$key]['type'] = $value->detailSampleViruses->map(function ($item) {
                return [
                    'name' => $item->virus->name,
                    'amount' => $item->detailSampleMorphotypes->map(function ($item) {
                        return $item->amount;
                    })->sum(),
                ];
            });
            $data[$key]['created_at'] = $value->created_at->format('Y-m-d');
        }

        // sum amount of same district by index
        $data = collect($data)->groupBy('district')->map(function ($item) {
            $amount = 0;
            foreach ($item as $key => $value) {
                $amount += $value['count'];
            }
            return [
                'district' => ucwords(strtolower($item[0]['district'])),
                'regency' => $item[0]['regency'],
                'count' => $amount,
                'type' => $item[0]['type'],
            ];
        });

        // change index to number
        $data = $data->values();

        // sort by count of sample
        $data = $data->sortByDesc('count');

        // get top 10
        $data = $data->take(20);

        return $data;
    }
}
