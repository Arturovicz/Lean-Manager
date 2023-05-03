les_query_sql_seront_ici  


## TP1-3 ============================================================================================================================================================================
drop database projecttp;
create database projecttp;
use projecttp;

## TABLE CREATION ============================================================================================================================================================================
create table if not exists orders(
	id int primary key,
    order_time datetime,
    completion_time datetime,
    order_status varchar(30) 
);

create table if not exists cart(
	id int primary key,
    wheel_color varchar(20),
    wheel_type varchar(30)
);

create table if not exists box(
	id int primary key 
);

create table if not exists sensors(
	sensor_id int primary key,
    sensor_type varchar(30) 
);

create table if not exists relays(
	relay_id int primary key,
    relay_type varchar(30) 
);

create table if not exists composition(
	box_id int,
	sensor_id int,
    relay_id int,
	sensor_amount int,
    relay_amount int,
    foreign key(sensor_id) references sensors(sensor_id),
    foreign key(relay_id) references relays(relay_id),
    foreign key(box_id) references box(id) on delete cascade
);

create table if not exists product(
	id int primary key,
    cart_id int,
    box_id int,
    foreign key(cart_id) references cart(id),
    foreign key(box_id) references box(id) on delete cascade
);

create table if not exists order_content(
	order_id int primary key,
    product_id int,
    product_amount int,
    foreign key(order_id) references orders(id),
    foreign key(product_id) references product(id) 
    on delete cascade
);

## TABLE INSERTION ============================================================================================================================================================================

insert into sensors values(1, 'CO2'), (2, 'Light'), (3, 'Humidity'), (4, 'Temperature'), (5, 'Pressure'), (6, 'Smoke');
insert into relays values(1, 'Mechatronic part I'), (2, 'Mechatronic part II'), (3, 'Lamp type I'), (4, 'Lamp type II');


insert into cart values(1, 'green', 'mobile'), (2, 'green', 'fix'), (3, 'red', 'mobile'), (4, 'green', 'fix'), (5, 'green', 'mobile'),
					   (6, 'red', 'fix'), (7, 'green', 'mobile'), (8, 'red', 'fix'), (9, 'red', 'mobile'), (10, 'green', 'fix');


select * from composition;


insert into orders values(1, '2023-02-13', NULL, 'ongoing'),
						 (2, '2023-02-13', NULL, 'ongoing'),
						 (3, '2023-02-13', NULL, 'ongoing'),
                         (4, '2023-02-13', NULL, 'ongoing'),
                         (5, '2023-02-13', NULL, 'ongoing');
insert into order_content values(1, 1, 1), (2, 2, 1), (3, 3, 1);
insert into product values(1, 1, 1), (2, 2, 2), (3, 3, 3);
insert into cart values(1, 'red', 'fix'), (2, 'green', 'not fix?');
insert into box values(1), (2), (3), (4), (5), (6), (7), (8), (9), (10); 
insert into composition values (1, 1, 1, 6, 3),
							   (2, 2, 2, 6, 3),
                               (3, 3, 3, 6, 3),
                               (4, 4, 4, 6, 3),
                               (5, 2, 2, 6, 3),
                               (6, 3, 3, 6, 3),
                               (7, 4, 4, 6, 3),
                               (8, 2, 2, 6, 3),
                               (9, 3, 3, 6, 3),
                               (10, 4, 4, 3, 2),
                               (10, 1, 2, 3, 1);





##=======================================TP4====================================================
create view quality_page as
SELECT q.order_id, q.product_type, q.product_id, q.state, f.flaw_type, f.severity
FROM quality_test q
LEFT JOIN flaw f ON q.flaw_id = f.id
ORDER BY q.order_id ASC


create table quality_test(
	order_id int,
    product_type varchar(30),
    product_id int,
    state varchar(20),
    flaw_id int,
    FOREIGN KEY(flaw_id) REFERENCES flaw(id));
    
    
create table flaw(
	id int PRIMARY KEY,
    flaw_type varchar(30),
    severity varchar(20)
);

create table flaw_count(
	flaw_type varchar(30),
    severity varchar(20),
    count int
);


insert into quality_test values(1, 'industrial box', 1, 'passed', NULL),
							   (1, 'cart', 1, 'minor flaw', 1),
                               (2, 'industrial box', 2, 'failed', 2),
                               (2, 'cart', 2, 'failed', 3),
                               (4, 'industrial box', 4, 'passed', NULL),
                               (4, 'cart',  4, 'passed', NULL),
                               (5, 'industrial box', 5, 'failed', 4),
                               (5, 'cart',  5, 'passed', NULL);
                               
                               

insert into flaw values(1, 'scratch', 'low'), (2, 'electircity', 'high'), (3, 'painting', 'medium'), (4, 'robustness', 'high');

insert into flaw_count values('scratch', 'low', 1), ('scratch', 'medium', 0), ('scratch', 'high', 0), 
							 ('painting', 'low', 0), ('painting', 'medium', 1), ('painting', 'high', 0),
							 ('electricity', 'low', 0), ('electricity', 'medium', 0), ('electricity', 'high', 1),
							 ('robustness', 'low', 0), ('robustness', 'medium', 0), ('robustness', 'high', 1);


#TRIGGER
DELIMITER $$
CREATE TRIGGER update_flaw_count
AFTER INSERT ON flaw
FOR EACH ROW
BEGIN
    IF NEW.flaw_type IN ('electricity', 'robustness', 'scratch', 'painting')
       AND NEW.severity IN ('low', 'medium', 'high') THEN
        UPDATE flaw_count
        SET count = count + 1
        WHERE flaw_type = NEW.flaw_type AND severity = NEW.severity;
        
        IF ROW_COUNT() = 0 THEN
            INSERT INTO flaw_count (flaw_type, severity, count)
            VALUES (NEW.flaw_type, NEW.severity, 1);
        END IF;
    END IF;
END $$
DELIMITER ;

