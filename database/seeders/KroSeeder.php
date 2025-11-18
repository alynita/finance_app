<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KroSeeder extends Seeder
{
    public function run()
    {
        $kroData = [
            [
                'kode' => '7949.EBA',
                'nama' => '7949.EBA',
                'children' => [
                    [
                        'kode' => '7949.EBA.Z07',
                        'nama' => '7949.EBA.Z07',
                        'children' => [
                            [
                                'kode' => '7949.EBA.Z07.701',
                                'nama' => '7949.EBA.Z07.701',
                                'children' => [
                                    [
                                        'kode' => 'A',
                                        'nama' => 'A',
                                        'children' => [
                                            ['kode_akun' => '524111', 'nama' => '524111']
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ],
                    [
                        'kode' => '7949.EBA.962',
                        'nama' => '7949.EBA.962',
                        'children' => [
                            [
                                'kode' => '7949.EBA.962.701',
                                'nama' => '7949.EBA.962.701',
                                'children' => [
                                    [
                                        'kode' => 'A',
                                        'nama' => 'A',
                                        'children' => [
                                            ['kode_akun' => '524111', 'nama' => '524111']
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ],
                    [
                        'kode' => '7949.EBA.994',
                        'nama' => '7949.EBA.994',
                        'children' => [
                            [
                                'kode' => '7949.EBA.994.001',
                                'nama' => '7949.EBA.994.001',
                                'children' => [
                                    [
                                        'kode' => 'A',
                                        'nama' => 'A',
                                        'children' => [
                                            ['kode_akun' => '511111', 'nama' => '511111'],
                                            ['kode_akun' => '511119', 'nama' => '511119'],
                                            ['kode_akun' => '511121', 'nama' => '511121'],
                                            ['kode_akun' => '511122', 'nama' => '511122'],
                                            ['kode_akun' => '511123', 'nama' => '511123'],
                                            ['kode_akun' => '511124', 'nama' => '511124'],
                                            ['kode_akun' => '511125', 'nama' => '511125'],
                                            ['kode_akun' => '511126', 'nama' => '511126'],
                                            ['kode_akun' => '511129', 'nama' => '511129'],
                                            ['kode_akun' => '511151', 'nama' => '511151'],
                                            ['kode_akun' => '511611', 'nama' => '511611'],
                                            ['kode_akun' => '511619', 'nama' => '511619'],
                                            ['kode_akun' => '511621', 'nama' => '511621'],
                                            ['kode_akun' => '511622', 'nama' => '511622'],
                                            ['kode_akun' => '511624', 'nama' => '511624'],
                                            ['kode_akun' => '511625', 'nama' => '511625'],
                                            ['kode_akun' => '511628', 'nama' => '511628'],
                                            ['kode_akun' => '512211', 'nama' => '512211'],
                                            ['kode_akun' => '512212', 'nama' => '512212'],
                                            ['kode_akun' => '512411', 'nama' => '512411'],
                                            ['kode_akun' => '512414', 'nama' => '512414'],
                                        ]
                                    ]
                                ]
                            ],
                            [
                                'kode' => '7949.EBA.994.002',
                                'nama' => '7949.EBA.994.002',
                                'children' => [
                                    [
                                        'kode' => 'A',
                                        'nama' => 'A',
                                        'children' => [
                                            ['kode_akun' => '521111', 'nama' => '521111'],
                                            ['kode_akun' => '521114', 'nama' => '521114'],
                                            ['kode_akun' => '521115', 'nama' => '521115'],
                                            ['kode_akun' => '521119', 'nama' => '521119'],
                                            ['kode_akun' => '521211', 'nama' => '521211'],
                                            ['kode_akun' => '521811', 'nama' => '521811'],
                                            ['kode_akun' => '522191', 'nama' => '522191'],
                                        ]
                                    ],
                                    [
                                        'kode' => 'B',
                                        'nama' => 'B',
                                        'children' => [
                                            ['kode_akun' => '524111', 'nama' => '524111'],
                                        ]
                                    ],
                                    [
                                        'kode' => 'C',
                                        'nama' => 'C',
                                        'children' => [
                                            ['kode_akun' => '522111', 'nama' => '522111'],
                                            ['kode_akun' => '522112', 'nama' => '522112'],
                                        ]
                                    ],
                                    [
                                        'kode' => 'D',
                                        'nama' => 'D',
                                        'children' => [
                                            ['kode_akun' => '523111', 'nama' => '523111'],
                                            ['kode_akun' => '523112', 'nama' => '523112'],
                                        ]
                                    ],
                                    [
                                        'kode' => 'E',
                                        'nama' => 'E',
                                        'children' => [
                                            ['kode_akun' => '523121', 'nama' => '523121'],
                                            ['kode_akun' => '523133', 'nama' => '523133'],
                                        ]
                                    ],
                                ]
                            ]
                        ]
                    ]
                ]
            ],

            // ===============================
            //     DATA 7949.EBD DIMULAI
            // ===============================

            [
                'kode' => '7949.EBD',
                'nama' => '7949.EBD',
                'children' => [
                    [
                        'kode' => '7949.EBD.Z25',
                        'nama' => '7949.EBD.Z25',
                        'children' => [
                            [
                                'kode' => '7949.EBD.Z25.701',
                                'nama' => '7949.EBD.Z25.701',
                                'children' => [
                                    [
                                        'kode' => 'A',
                                        'nama' => 'A',
                                        'children' => [
                                            ['kode_akun' => '524111', 'nama' => '524111'],
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ],
                    [
                        'kode' => '7949.EBD.Z27',
                        'nama' => '7949.EBD.Z27',
                        'children' => [
                            [
                                'kode' => '7949.EBD.Z27.701',
                                'nama' => '7949.EBD.Z27.701',
                                'children' => [
                                    [
                                        'kode' => 'A',
                                        'nama' => 'A',
                                        'children' => [
                                            ['kode_akun' => '524111', 'nama' => '524111'],
                                        ]
                                    ],
                                    [
                                        'kode' => 'B',
                                        'nama' => 'B',
                                        'children' => [
                                            ['kode_akun' => '524111', 'nama' => '524111'],
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ],
                    [
                        'kode' => '7949.EBD.Z34',
                        'nama' => '7949.EBD.Z34',
                        'children' => [
                            [
                                'kode' => '7949.EBD.Z34.701',
                                'nama' => '7949.EBD.Z34.701',
                                'children' => [
                                    [
                                        'kode' => 'A',
                                        'nama' => 'A',
                                        'children' => [
                                            ['kode_akun' => '524111', 'nama' => '524111'],
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ],
                ]
            ],
            // ===============================
            //     DATA 6793.AEA 
            // ===============================
            [
                'kode' => '6793.AEA',
                'nama' => '6793.AEA',
                'children' => [
                    [
                        'kode' => '6793.AEA.550',
                        'nama' => '6793.AEA.550',
                        'children' => [
                            [
                                'kode' => '6793.AEA.550.601',
                                'nama' => '6793.AEA.550.601',
                                'children' => [
                                    [
                                        'kode' => 'A',
                                        'nama' => 'A',
                                        'children' => [
                                            ['kode_akun' => '521211', 'nama' => '521211'],
                                            ['kode_akun' => '521219', 'nama' => '521219'],
                                            ['kode_akun' => '524111', 'nama' => '524111'],
                                        ]
                                    ],
                                    [
                                        'kode' => 'B',
                                        'nama' => 'B',
                                        'children' => [
                                            ['kode_akun' => '532111', 'nama' => '532111'],
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            // ===============================
            //     DATA 6793.EBC
            // ===============================
            [
                'kode' => '6793.EBC',
                'nama' => '6793.EBC',
                'children' => [
                    [
                        'kode' => '6793.EBC.996',
                        'nama' => '6793.EBC.996',
                        'children' => [
                            [
                                'kode' => '6793.EBC.996.603',
                                'nama' => '6793.EBC.996.603',
                                'children' => [
                                    [
                                        'kode' => 'A',
                                        'nama' => 'A',
                                        'children' => [
                                            ['kode_akun' => '522151', 'nama' => '522151'],
                                        ]
                                    ],
                                    [
                                        'kode' => 'B',
                                        'nama' => 'B',
                                        'children' => [
                                            ['kode_akun' => '521211', 'nama' => '521211'],
                                            ['kode_akun' => '521219', 'nama' => '521219'],
                                            ['kode_akun' => '522141', 'nama' => '522141'],
                                            ['kode_akun' => '522151', 'nama' => '522151'],
                                            ['kode_akun' => '524111', 'nama' => '524111'],
                                            ['kode_akun' => '524113', 'nama' => '524113'],
                                            ['kode_akun' => '524119', 'nama' => '524119'],
                                            ['kode_akun' => '521211', 'nama' => '521211'],
                                            ['kode_akun' => '521219', 'nama' => '521219'],
                                            ['kode_akun' => '522141', 'nama' => '522141'],
                                            ['kode_akun' => '522151', 'nama' => '522151'],
                                            ['kode_akun' => '524111', 'nama' => '524111'],
                                            ['kode_akun' => '524113', 'nama' => '524113'],
                                            ['kode_akun' => '524119', 'nama' => '524119'],
                                        ]
                                    ],
                                    [
                                        'kode' => 'C',
                                        'nama' => 'C',
                                        'children' => [
                                            ['kode_akun' => '521211', 'nama' => '521211'],
                                            ['kode_akun' => '522151', 'nama' => '522151'],
                                            ['kode_akun' => '524111', 'nama' => '524111'],
                                            ['kode_akun' => '524113', 'nama' => '524113'],
                                            ['kode_akun' => '521211', 'nama' => '521211'],
                                            ['kode_akun' => '522151', 'nama' => '522151'],
                                            ['kode_akun' => '524111', 'nama' => '524111'],
                                            ['kode_akun' => '524113', 'nama' => '524113'],
                                            ['kode_akun' => '521211', 'nama' => '521211'],
                                            ['kode_akun' => '522151', 'nama' => '522151'],
                                            ['kode_akun' => '524111', 'nama' => '524111'],
                                            ['kode_akun' => '524113', 'nama' => '524113'],
                                            ['kode_akun' => '521211', 'nama' => '521211'],
                                            ['kode_akun' => '522151', 'nama' => '522151'],
                                            ['kode_akun' => '524113', 'nama' => '524113'],
                                            ['kode_akun' => '521211', 'nama' => '521211'],
                                            ['kode_akun' => '522141', 'nama' => '522141'],
                                            ['kode_akun' => '522151', 'nama' => '522151'],
                                            ['kode_akun' => '524111', 'nama' => '524111'],
                                            ['kode_akun' => '524113', 'nama' => '524113'],
                                            ['kode_akun' => '521211', 'nama' => '521211'],
                                            ['kode_akun' => '522151', 'nama' => '522151'],
                                            ['kode_akun' => '524111', 'nama' => '524111'],
                                            ['kode_akun' => '524113', 'nama' => '524113'],
                                            
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        // Recursive insert
        $insertKRO = function ($items, $parentId = null) use (&$insertKRO) {
            foreach ($items as $item) {
                $data = [
                    'kode' => $item['kode'] ?? null,
                    'nama' => $item['nama'] ?? null,
                    'parent_id' => $parentId,
                    'kode_akun' => $item['kode_akun'] ?? null,
                ];

                $id = DB::table('kro')->insertGetId($data);

                if (isset($item['children'])) {
                    $insertKRO($item['children'], $id);
                }
            }
        };

        $insertKRO($kroData);
    }
}
