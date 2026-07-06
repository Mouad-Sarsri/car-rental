-- manager_id : Youssef(2), Fatima(3), Karim(4)
INSERT INTO agencies (manager_id, nom, adresse, ville, code_postal, telephone, email, description, actif) VALUES

(2, 'CarRental Casablanca Centre',
    '25 Boulevard Mohammed V', 'Casablanca', '20000',
    '+212522000001', 'casa.centre@carrental.ma',
    'Agence principale au cœur de Casablanca, à 5 min de la gare.', 1),

(3, 'CarRental Rabat Agdal',
    '14 Avenue Fal Ould Oumeir', 'Rabat', '10000',
    '+212537000002', 'rabat.agdal@carrental.ma',
    'Agence moderne proche du quartier diplomatique.', 1),

(4, 'CarRental Marrakech Gueliz',
    '52 Avenue Mohammed VI', 'Marrakech', '40000',
    '+212524000003', 'marrakech@carrental.ma',
    'Location de voitures pour explorer la ville rouge.', 1),

(NULL, 'CarRental Tanger Médina',
    '8 Rue de la Plage', 'Tanger', '90000',
    '+212539000004', 'tanger@carrental.ma',
    'Agence stratégique proche du port et de la médina.', 1);
