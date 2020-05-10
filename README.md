# drosophila-genmicroarray-lifecycle-website
I am including here the files that powered the website we created about 20 years ago (2003) to offer a massive amount of gene expression data for the Drosophila Melanogaster lifecycle.  This study, published by Kevin White in 2002 in the Science mag (Sep-2002), covers the genome wide expression for 72 points in the life of the fruitfly, from larvae to adult stages.
This was done when Nirvana was still a thing, for today's standards, this is dated technology, but back when, it allowed researchers over the world to see how their favorite genes may be co-expressed in different stages of the fruitfly.

We used a mySQL backend database (mysql was then NOT owned by Oracle).  Six tables comprise the DB:
1) element_table: 12 cols  
     elem_id int pk  
     raid varchar(25)  
     status enum('confirmed','unconfirmed','refuted')  
     cg varchar(9) -- the gene celera ID  
     prot_domain  text -- a descriptive of the assoc prot.  
     chr_pos var_char(15)  
     fbgn varchar(14)  -- flybase id  
     com_gen_name varchar(15) -- gene name  
     fct  varchar(20) -- function  
     est varchar(9)  
     fct_clean varchar(20)  
     mixed_annot text   
 2) exon_table: 8 cols  
      xml_id int pj  
      ae  varchar(8)  
      cg  varchar(9)  
      fbgn varchar(14)  
      ct varchar(12)  
      exon int  
      first_pos int  
      last_pos int  
  3) exp_table: 10 cols  
      local_exp_id int pk  
      exp_id int  
      raid char(14)  
      ratio double  
      log_ratio double  
      rep_average double  
      ch1_ave  int  
      ch2_ave int  
      ch1_signal int  
  4)  experiment_description1: 8 cols  
      index_exp_id int pk  
      exp_id int 
      stage enum('embryo','larvae','prepupae'.'pupae','adult')  
      time_point_young double  
      time_point_old double  
      sex enum('both','male','female')  
      description enum('CS wild type','CS unfertilized','tudor','eyes absent')  
      replicate int -- this is 1 or 2  
   5) red_element_table : this table is like element_table, but add one field  
      cluster_index varchar(20)  
   6) red_exp_table : this table is like exp_table, but drops most columns, and adds cluster_index  
      local_red_exp_id int pk  
      exp_id int  
      raid  char(20)  
      rep_average double  
      cluster_index char(20)  
      
      I may add the SQl files to populate the data in this DB.
      
   
