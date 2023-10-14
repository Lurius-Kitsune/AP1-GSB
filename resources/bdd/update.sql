-- US 2  Ajout de la table comptable 
create table  if not exists comptable (
    id int not null auto_increment,
    nom varchar(50) not null,
    prenom varchar(50) not null,
    login varchar(50) not null,
    mdp CHAR(128) not null,
		primary key (id)
        );


-- FEATURE/MDPCRYPT Modification des mdp pour les mettre en SHA2-512
ALTER TABLE visiteur MODIFY mdp CHAR(128) ;

UPDATE visiteur SET mdp = sha2(mdp, 512);

UPDATE comptable SET mdp = sha2(mdp, 512);