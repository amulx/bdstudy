CREATE TABLE `test_nums` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='参考表';


CREATE TABLE `test_sign_history` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '签到时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='签到历史表';

insert into test_nums (id) values (7);
insert into test_nums (id) values (8);
insert into test_nums (id) values (9);
insert into test_nums (id) values (10);
insert into test_nums (id) values (11);
insert into test_nums (id) values (12);
insert into test_nums (id) values (13);
insert into test_nums (id) values (14);
insert into test_nums (id) values (15);
insert into test_nums (id) values (16);
insert into test_nums (id) values (17);
insert into test_nums (id) values (18);
insert into test_nums (id) values (19);
insert into test_nums (id) values (20);
insert into test_nums (id) values (21);
insert into test_nums (id) values (22);
insert into test_nums (id) values (23);
insert into test_nums (id) values (24);
insert into test_nums (id) values (25);
insert into test_nums (id) values (26);
insert into test_nums (id) values (27);
insert into test_nums (id) values (28);

insert into test_sign_history(uid,create_time)
select ceil(rand()*10000),str_to_date('2016-12-11','%Y-%m-%d')+interval ceil(rand()*10000) minute 
from test_nums where id<31;



//统计每天的每小时用户签到情况
select
    h,
    sum(case when create_time='2016-12-11' then c else 0 end) 11Sign,
    sum(case when create_time='2016-12-12' then c else 0 end) 12Sign,
    sum(case when create_time='2016-12-13' then c else 0 end) 13Sign,
    sum(case when create_time='2016-12-14' then c else 0 end) 14Sign,
    sum(case when create_time='2016-12-15' then c else 0 end) 15Sign,
    sum(case when create_time='2016-12-16' then c else 0 end) 16Sign,
    sum(case when create_time='2016-12-17' then c else 0 end) 17Sign
from
(
    select
        date_format(create_time,'%Y-%m-%d') create_time,
        hour(create_time) h,
        count(*) c
    from test_sign_history
    group by
        date_format(create_time,'%Y-%m-%d'),
        hour(create_time)
) a
group by h with rollup;




select h,
       sum(case when iDate='2017-04-20 00' then c else 0 end) 00sign,
       sum(case when iDate='2017-04-20 01' then c else 0 end) 01sign,
       sum(case when iDate='2017-04-20 02' then c else 0 end) 02sign,
       sum(case when iDate='2017-04-20 03' then c else 0 end) 03sign,
       sum(case when iDate='2017-04-20 04' then c else 0 end) 04sign,
       sum(case when iDate='2017-04-20 05' then c else 0 end) 05sign,
       sum(case when iDate='2017-04-20 06' then c else 0 end) 06sign,
       sum(case when iDate='2017-04-20 07' then c else 0 end) 07sign,
       sum(case when iDate='2017-04-20 08' then c else 0 end) 08sign,
       sum(case when iDate='2017-04-20 09' then c else 0 end) 09sign,
       sum(case when iDate='2017-04-20 10' then c else 0 end) 10sign,
       sum(case when iDate='2017-04-20 11' then c else 0 end) 11sign,
       sum(case when iDate='2017-04-20 12' then c else 0 end) 12sign,
       sum(case when iDate='2017-04-20 13' then c else 0 end) 13sign,
       sum(case when iDate='2017-04-20 14' then c else 0 end) 14sign,
       sum(case when iDate='2017-04-20 15' then c else 0 end) 15sign,
       sum(case when iDate='2017-04-20 16' then c else 0 end) 16sign
from
(
       select
             FROM_UNIXTIME(iDate,'%Y-%m-%d %H') iDate,
             FROM_UNIXTIME(iDate,'%H') h,
             count(*)  c
       from m_tb_ftp_log_20170420
       group by
             FROM_UNIXTIME(iDate,'%Y-%m-%d %H')
) a
group by h with rollup;

select iDate, sAppProto from m_tb_ftp_log_20170420 group by sAppProto


select
    h,
    sum(case when create_time='2016-12-11' then c else 0 end) 11Sign,
    sum(case when create_time='2016-12-12' then c else 0 end) 12Sign,
    sum(case when create_time='2016-12-13' then c else 0 end) 13Sign,
    sum(case when create_time='2016-12-14' then c else 0 end) 14Sign,
    sum(case when create_time='2016-12-15' then c else 0 end) 15Sign,
    sum(case when create_time='2016-12-16' then c else 0 end) 16Sign,
    sum(case when create_time='2016-12-17' then c else 0 end) 17Sign
from
(
    select
        date_format(create_time,'%Y-%m-%d') create_time,
        hour(create_time) h,
        count(*) c
    from test_sign_history
    group by
        date_format(create_time,'%Y-%m-%d'),
        hour(create_time)
) a
group by h with rollup;

select sAppType ,iCount, hour(FROM_UNIXTIME(iTime)) as hour FROM m_tb_statistics_audit_hour_20170329 group by hour(FROM_UNIXTIME(iTime)),sAppType
