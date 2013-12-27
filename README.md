opencart-development-jne
========================

Opencart development shipping JNE

Rewrite on ubuntu
-----------------

chmod -R 777 opencart-development-jne/


Configuration
-------------

1. Aktifkan extension shipping JNE

*Disable Flat Rate*

Admin -> extension -> shipping -> JNE Rate -> install

kemudian Edit :

Status     : Enabled
Sort Order : 1

2. Tambah mata uang Rupiah 

Admin -> System -> localisation -> currency -> insert

Currency Title 	: Rupiah
Code 			: IDR
Symbol Left		: Rp. 
Status			: Enabled

3. Edit zona indonesia

Admin -> System -> localisation -> zone

...&page=76

*Disable* BoDeTaBek
*Edit* Jakarta Raya to Jakarta

4. Edit dimension & berat produk

http://www.apple.com/iphone-4s/specs/
http://www.apple.com/macbook-pro/specs-retina/
http://www.apple.com/macbook-air/specs.html


Atau import ulang SQL
---------------------