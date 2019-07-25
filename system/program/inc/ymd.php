<?php

function YearNextX($num,$default = '--'){
	$years = array();
	$years[0] = $default;
	for ($i=1; $i <= $num+1; $i++) { 
		$years[$i] = date('Y') + $i -1;
	}
	return $years;
};

/*
function YearPastX($num_start,$num_end,$default = '--'){
	$years = array();
	$years[0] = $default;
	for ($i=1; $i <= $num+1; $i++) {
		if($i >= $num_start)
			$years[$i] = date('Y') + $i -1;
	}
	return $years;
};
*/

function YearMove(){
	return array(
		0  => '--',
		1  => date('Y'),
		2  => date('Y') + 1,
		3  => date('Y') + 2,
		4  => date('Y') + 3,
		5  => date('Y') + 4,
		6  => date('Y') + 5,
		7  => date('Y') + 6,
		8  => date('Y') + 7,
		9  => date('Y') + 8,
		10 => date('Y') + 9,
	);
};

function YearPastMove(){
	return array(
		0  => '--',
		1   => date('Y') - 70,
		2   => date('Y') - 69,
		3   => date('Y') - 68,
		4   => date('Y') - 67,
		5   => date('Y') - 66,
		6   => date('Y') - 65,
		7   => date('Y') - 64,
		8   => date('Y') - 63,
		9   => date('Y') - 62,
		10  => date('Y') - 61,
		11  => date('Y') - 60,
		12  => date('Y') - 59,
		13  => date('Y') - 58,
		14  => date('Y') - 57,
		15  => date('Y') - 56,
		16  => date('Y') - 55,
		17  => date('Y') - 54,
		18  => date('Y') - 53,
		19  => date('Y') - 52,
		20  => date('Y') - 51,
		21  => date('Y') - 50,
		22  => date('Y') - 49,
		23  => date('Y') - 48,
		24  => date('Y') - 47,
		25  => date('Y') - 46,
		26  => date('Y') - 45,
		27  => date('Y') - 44,
		28  => date('Y') - 43,
		29  => date('Y') - 42,
		30  => date('Y') - 41,
		31  => date('Y') - 40,
		32  => date('Y') - 39,
		33  => date('Y') - 38,
		34  => date('Y') - 37,
		35  => date('Y') - 36,
		36  => date('Y') - 35,
		37  => date('Y') - 34,
		38  => date('Y') - 33,
		39  => date('Y') - 32,
		40  => date('Y') - 31,
		41  => date('Y') - 30,
		42  => date('Y') - 29,
		43  => date('Y') - 28,
		44  => date('Y') - 27,
		45  => date('Y') - 26,
		46  => date('Y') - 25,
		47  => date('Y') - 24,
		48  => date('Y') - 23,
		49  => date('Y') - 22,
		50  => date('Y') - 21,
		51  => date('Y') - 20,
		52  => date('Y') - 19,
		53  => date('Y') - 18,
		54  => date('Y') - 17,
	);
};

function YearPastMoveDesc(){
	return array(
		0  => '--',
		1   => date('Y') - 15,
		2   => date('Y') - 16,
		3   => date('Y') - 17,
		4   => date('Y') - 18,
		5   => date('Y') - 19,
		6   => date('Y') - 20,
		7   => date('Y') - 21,
		8   => date('Y') - 22,
		9   => date('Y') - 23,
		10  => date('Y') - 24,
		11  => date('Y') - 25,
		12  => date('Y') - 26,
		13  => date('Y') - 27,
		14  => date('Y') - 28,
		15  => date('Y') - 29,
		16  => date('Y') - 30,
		17  => date('Y') - 31,
		18  => date('Y') - 32,
		19  => date('Y') - 33,
		20  => date('Y') - 34,
		21  => date('Y') - 35,
		22  => date('Y') - 36,
		23  => date('Y') - 37,
		24  => date('Y') - 38,
		25  => date('Y') - 39,
		26  => date('Y') - 40,
		27  => date('Y') - 41,
		28  => date('Y') - 42,
		29  => date('Y') - 43,
		30  => date('Y') - 44,
		31  => date('Y') - 45,
		32  => date('Y') - 46,
		33  => date('Y') - 47,
		34  => date('Y') - 48,
		35  => date('Y') - 49,
		36  => date('Y') - 50,
		37  => date('Y') - 51,
		38  => date('Y') - 52,
		39  => date('Y') - 53,
		40  => date('Y') - 54,
		41  => date('Y') - 55,
		42  => date('Y') - 56,
		43  => date('Y') - 57,
		44  => date('Y') - 58,
		45  => date('Y') - 59,
	);
};

function Month(){
	return array(
		0  => '--',
		1  => '1',
		2  => '2',
		3  => '3',
		4  => '4',
		5  => '5',
		6  => '6',
		7  => '7',
		8  => '8',
		9  => '9',
		10 => '10',
		11 => '11',
		12 => '12',
	);
};

function Day(){
	return array(
		0  => '--',
		1  => '1',
		2  => '2',
		3  => '3',
		4  => '4',
		5  => '5',
		6  => '6',
		7  => '7',
		8  => '8',
		9  => '9',
		10 => '10',
		11 => '11',
		12 => '12',
		13 => '13',
		14 => '14',
		15 => '15',
		16 => '16',
		17 => '17',
		18 => '18',
		19 => '19',
		20 => '20',
		21 => '21',
		22 => '22',
		23 => '23',
		24 => '24',
		25 => '25',
		26 => '26',
		27 => '27',
		28 => '28',
		29 => '29',
		30 => '30',
		31 => '31',
	);
};




?>