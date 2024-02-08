INSERT INTO `users` VALUES
    (1,'a@a.com','$2y$10$3WLdKSvYLcTw/LzpS5eQVeEy5gqABjryXW9YCQnxeDhvQ0SdAySjS',0),
    (2,'recruiting@canonical.com','$2y$10$NRotSVdq5ws/pRnhHnQWGuEKAPkfMPMcWuNA4svFggxwDLgqZFMQS',1),
    (3,'a@b.com','$2y$10$I.haJwLf8kwtf8cDmoz6BujmrYKBHOc5zt1NzFeHlOOCuhNrsVIem',1);

INSERT INTO `profiles` VALUES
    (1,'Mauro','M','','../imgs/profiles/1/pic.png',''),
    (2,'John','Canonical','','../imgs/profiles/2/pic.jpg',''),
    (3,'asdfasdf','asdfasdfadf','',NULL,'');

INSERT INTO jobs(title, description, company, creation_date, recruiter_id) VALUES
    ('Senior Systems Engineer - Embedded Linux Optimisation', "Work across the full Linux stack from kernel through networking, virtualization and graphics to optimise Ubuntu, the world's most widely used Linux desktop and server, for the latest silicon.

The role is a fast-paced, problem-solving role that's challenging yet very exciting. The right candidate must be resourceful, articulate, and able to deliver on a wide variety of solutions across Server, PC and IoT technologies. Our teams partner with specialist engineers from major silicon companies to integrate next-generation features and performance enhancements for upcoming hardware.", 'Canonical', '2024-01-11', 2
    ), ("Senior Firmware Engineer", "Candam Technologies S.L. aporta tecnología para la Economía Circular. Con sede en Barcelona, nos esforzamos por mejorar la Sostenibilidad y las Ciudades Inteligentes.


Lideramos la innovación en el sector de la gestión de residuos mediante el diseño y la distribución de herramientas de hardware y software patentadas para esquemas de recompensas, reembolso de depósitos y envases reutilizables.


En pleno proceso de crecimiento, la empresa está expandiendo su visibilidad tanto en España como en el resto de Europa. Actualmente, el proceso de prescripción de la propuesta de valor es clave de cara a que se puedan incluir en las licitaciones a lanzar, por esto necesitamos personas con experiencia en el sector que nos ayuden a aumentar nuestras oportunidades.", "Candam Tech", "2023-12-25", 3
    ), ("Embedded Software Engineer", "D.med Software S.L.U. is part of D.Med Consulting GmbH, a joint venture between Fresenius Medical Care AG & Co. KGaA (51%) and the D.Med Healthcare Group (49%), one of the world’s leading operators of medical devices and medical products in the field of Nephrology, Cardiology and Internal Medicine. Founded in 2011 and seated in Hamburg, D.Med Consulting GmbH provides first-class consulting services focused on Dialysis.


D.med Software is a fast-growing company with focus on developing software systems for the medical industry with the vision to be the leading software service provider for the medical industry in cybersecurity for embedded software & cloud applications.", "D.med Healthcare Group", "2024-01-2", 3);
