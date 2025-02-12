PGDMP  +    8                |            librarymanager    16.0    16.2                0    0    ENCODING    ENCODING        SET client_encoding = 'UTF8';
                      false                       0    0 
   STDSTRINGS 
   STDSTRINGS     (   SET standard_conforming_strings = 'on';
                      false                       0    0 
   SEARCHPATH 
   SEARCHPATH     8   SELECT pg_catalog.set_config('search_path', '', false);
                      false                       1262    24624    librarymanager    DATABASE     �   CREATE DATABASE librarymanager WITH TEMPLATE = template0 ENCODING = 'UTF8' LOCALE_PROVIDER = libc LOCALE = 'French_France.1252';
    DROP DATABASE librarymanager;
                HP PC    false                       0    0    DATABASE librarymanager    ACL     /   GRANT ALL ON DATABASE librarymanager TO admin;
                   HP PC    false    4868                        2615    2200    public    SCHEMA        CREATE SCHEMA public;
    DROP SCHEMA public;
                pg_database_owner    false                       0    0    SCHEMA public    COMMENT     6   COMMENT ON SCHEMA public IS 'standard public schema';
                   pg_database_owner    false    4            �            1255    41017    findusers(character) 	   PROCEDURE     �   CREATE PROCEDURE public.findusers(IN initial character)
    LANGUAGE plpgsql
    AS $$ BEGIN PERFORM * FROM users WHERE email LIKE initial || '%';  END; $$;
 7   DROP PROCEDURE public.findusers(IN initial character);
       public          HP PC    false    4            �            1255    32830    isexist(text)    FUNCTION     +  CREATE FUNCTION public.isexist(_users text) RETURNS boolean
    LANGUAGE plpgsql STABLE
    AS $$DECLARE
    user_count INT;
BEGIN
    SELECT COUNT(*) INTO user_count FROM users WHERE email = _users;
    IF user_count = 0 THEN
        RETURN false;
    ELSE
        RETURN true;
    END IF;
END;$$;
 +   DROP FUNCTION public.isexist(_users text);
       public          HP PC    false    4            �            1259    73792    emprunts    TABLE     �   CREATE TABLE public.emprunts (
    id_emprunts character varying(100),
    email character varying(100),
    code_barre character varying(13),
    qte integer,
    date_emp character varying(100),
    date_retour character varying(100)
);
    DROP TABLE public.emprunts;
       public         heap    HP PC    false    4            �            1259    24633    livres    TABLE     �   CREATE TABLE public.livres (
    titre character varying(100) NOT NULL,
    auteur character varying(100) NOT NULL,
    date_ed date NOT NULL,
    code_barre character varying(13) NOT NULL,
    stock integer
);
    DROP TABLE public.livres;
       public         heap    HP PC    false    4            �            1259    81977    notifications    TABLE     �   CREATE TABLE public.notifications (
    de character varying(100),
    a character varying(100),
    messages text,
    date_env character varying(50)
);
 !   DROP TABLE public.notifications;
       public         heap    HP PC    false    4            �            1259    65593    stock    TABLE     h   CREATE TABLE public.stock (
    codebarre character varying(13) NOT NULL,
    total integer NOT NULL
);
    DROP TABLE public.stock;
       public         heap    postgres    false    4            �            1259    90173    users    TABLE     �   CREATE TABLE public.users (
    id integer NOT NULL,
    nom character varying(100) NOT NULL,
    email character varying(100) NOT NULL,
    mdp character varying(13) NOT NULL,
    role character varying(100)
);
    DROP TABLE public.users;
       public         heap    HP PC    false    4            �            1259    90172    users_id_seq    SEQUENCE     �   CREATE SEQUENCE public.users_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 #   DROP SEQUENCE public.users_id_seq;
       public          HP PC    false    220    4                       0    0    users_id_seq    SEQUENCE OWNED BY     =   ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;
          public          HP PC    false    219            c           2604    90176    users id    DEFAULT     d   ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);
 7   ALTER TABLE public.users ALTER COLUMN id DROP DEFAULT;
       public          HP PC    false    219    220    220            �          0    73792    emprunts 
   TABLE DATA           ^   COPY public.emprunts (id_emprunts, email, code_barre, qte, date_emp, date_retour) FROM stdin;
    public          HP PC    false    217   E       �          0    24633    livres 
   TABLE DATA           K   COPY public.livres (titre, auteur, date_ed, code_barre, stock) FROM stdin;
    public          HP PC    false    215          �          0    81977    notifications 
   TABLE DATA           B   COPY public.notifications (de, a, messages, date_env) FROM stdin;
    public          HP PC    false    218   �       �          0    65593    stock 
   TABLE DATA           1   COPY public.stock (codebarre, total) FROM stdin;
    public          postgres    false    216   �       �          0    90173    users 
   TABLE DATA           :   COPY public.users (id, nom, email, mdp, role) FROM stdin;
    public          HP PC    false    220   �                  0    0    users_id_seq    SEQUENCE SET     :   SELECT pg_catalog.setval('public.users_id_seq', 6, true);
          public          HP PC    false    219            g           2606    73796 !   emprunts emprunts_id_emprunts_key 
   CONSTRAINT     c   ALTER TABLE ONLY public.emprunts
    ADD CONSTRAINT emprunts_id_emprunts_key UNIQUE (id_emprunts);
 K   ALTER TABLE ONLY public.emprunts DROP CONSTRAINT emprunts_id_emprunts_key;
       public            HP PC    false    217            e           2606    24637    livres livres_code_barre_key 
   CONSTRAINT     ]   ALTER TABLE ONLY public.livres
    ADD CONSTRAINT livres_code_barre_key UNIQUE (code_barre);
 F   ALTER TABLE ONLY public.livres DROP CONSTRAINT livres_code_barre_key;
       public            HP PC    false    215            i           2606    90178    users users_pkey 
   CONSTRAINT     N   ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);
 :   ALTER TABLE ONLY public.users DROP CONSTRAINT users_pkey;
       public            HP PC    false    220            �   �   x�u��
�@E�ݯ�ff�Le Z�6�͢"�D%&�z�sc�^8�s�EI+�Mu_^�P����(��HL
�LB��\�k7BB��s�n���,A\&�2,{nUw���5��1
��m�0�a�F��<�a���/�b�h##H��k�o�u~����h�X��1�Z T�Y�      �   �   x�U�91��9��)�ݔ4)(S�8?@�]���L���GF�5��q�R[b L�q�c�C�F�d�ș��U\��n��JF�$CT,%�����H#[qbg�4�c�~]"�}��5��Y�HZ-��G˪e�6Rl͔$����Z�8 ��.^X�q�Rz]�E�      �   �   x���1�0Й�� 9!��s��%*V5Co�s�bEL�L�_��mS{ۜn�X�][��E>�~`����#2�m�摟�s3M�����]���~%
�N1Oe� ����r�ږB��dI���$(��S;��-�Hkq7�|�^�5$Ix�����`��19a��m�k>����4R^�K&�� *c'�      �      x�3761273�04546�44������ 0��      �   �   x����
�0Dϻ_�/(�U�*ފ"����,��d%��1�E^��̃�-���fX�<�k��'������4)P����������6pШ��R�VF'����#G%�=�12����ɜ9EyA���x�/"��qR3     