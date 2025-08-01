PGDMP  5                    }            education_platform    16.6    16.6 u    �           0    0    ENCODING    ENCODING        SET client_encoding = 'UTF8';
                      false            �           0    0 
   STDSTRINGS 
   STDSTRINGS     (   SET standard_conforming_strings = 'on';
                      false            �           0    0 
   SEARCHPATH 
   SEARCHPATH     8   SELECT pg_catalog.set_config('search_path', '', false);
                      false            �           1262    17894    education_platform    DATABASE     �   CREATE DATABASE education_platform WITH TEMPLATE = template0 ENCODING = 'UTF8' LOCALE_PROVIDER = libc LOCALE = 'English_Indonesia.1252';
 "   DROP DATABASE education_platform;
                postgres    false            �            1259    18784    cache    TABLE     �   CREATE TABLE public.cache (
    key character varying(255) NOT NULL,
    value text NOT NULL,
    expiration integer NOT NULL
);
    DROP TABLE public.cache;
       public         heap    postgres    false            �            1259    18791    cache_locks    TABLE     �   CREATE TABLE public.cache_locks (
    key character varying(255) NOT NULL,
    owner character varying(255) NOT NULL,
    expiration integer NOT NULL
);
    DROP TABLE public.cache_locks;
       public         heap    postgres    false            �            1259    18885    comments    TABLE       CREATE TABLE public.comments (
    id bigint NOT NULL,
    course_id bigint NOT NULL,
    user_id bigint NOT NULL,
    content text NOT NULL,
    parent_id bigint,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
    DROP TABLE public.comments;
       public         heap    postgres    false            �            1259    18884    comments_id_seq    SEQUENCE     x   CREATE SEQUENCE public.comments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 &   DROP SEQUENCE public.comments_id_seq;
       public          postgres    false    233            �           0    0    comments_id_seq    SEQUENCE OWNED BY     C   ALTER SEQUENCE public.comments_id_seq OWNED BY public.comments.id;
          public          postgres    false    232            �            1259    18871    course_contents    TABLE     2  CREATE TABLE public.course_contents (
    id bigint NOT NULL,
    course_id bigint NOT NULL,
    content text NOT NULL,
    content_type character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    "order" integer DEFAULT 0 NOT NULL
);
 #   DROP TABLE public.course_contents;
       public         heap    postgres    false            �            1259    18870    course_contents_id_seq    SEQUENCE        CREATE SEQUENCE public.course_contents_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 -   DROP SEQUENCE public.course_contents_id_seq;
       public          postgres    false    231            �           0    0    course_contents_id_seq    SEQUENCE OWNED BY     Q   ALTER SEQUENCE public.course_contents_id_seq OWNED BY public.course_contents.id;
          public          postgres    false    230            �            1259    18951    course_user    TABLE     �   CREATE TABLE public.course_user (
    id bigint NOT NULL,
    course_id bigint NOT NULL,
    user_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
    DROP TABLE public.course_user;
       public         heap    postgres    false            �            1259    18950    course_user_id_seq    SEQUENCE     {   CREATE SEQUENCE public.course_user_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 )   DROP SEQUENCE public.course_user_id_seq;
       public          postgres    false    240            �           0    0    course_user_id_seq    SEQUENCE OWNED BY     I   ALTER SEQUENCE public.course_user_id_seq OWNED BY public.course_user.id;
          public          postgres    false    239            �            1259    18856    courses    TABLE     }  CREATE TABLE public.courses (
    id bigint NOT NULL,
    title character varying(255) NOT NULL,
    description text NOT NULL,
    thumbnail character varying(255) NOT NULL,
    status character varying(255) DEFAULT 'pending'::character varying NOT NULL,
    user_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
    DROP TABLE public.courses;
       public         heap    postgres    false            �            1259    18855    courses_id_seq    SEQUENCE     w   CREATE SEQUENCE public.courses_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 %   DROP SEQUENCE public.courses_id_seq;
       public          postgres    false    229            �           0    0    courses_id_seq    SEQUENCE OWNED BY     A   ALTER SEQUENCE public.courses_id_seq OWNED BY public.courses.id;
          public          postgres    false    228            �            1259    18816    failed_jobs    TABLE     &  CREATE TABLE public.failed_jobs (
    id bigint NOT NULL,
    uuid character varying(255) NOT NULL,
    connection text NOT NULL,
    queue text NOT NULL,
    payload text NOT NULL,
    exception text NOT NULL,
    failed_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);
    DROP TABLE public.failed_jobs;
       public         heap    postgres    false            �            1259    18815    failed_jobs_id_seq    SEQUENCE     {   CREATE SEQUENCE public.failed_jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 )   DROP SEQUENCE public.failed_jobs_id_seq;
       public          postgres    false    223            �           0    0    failed_jobs_id_seq    SEQUENCE OWNED BY     I   ALTER SEQUENCE public.failed_jobs_id_seq OWNED BY public.failed_jobs.id;
          public          postgres    false    222            �            1259    18808    job_batches    TABLE     d  CREATE TABLE public.job_batches (
    id character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    total_jobs integer NOT NULL,
    pending_jobs integer NOT NULL,
    failed_jobs integer NOT NULL,
    failed_job_ids text NOT NULL,
    options text,
    cancelled_at integer,
    created_at integer NOT NULL,
    finished_at integer
);
    DROP TABLE public.job_batches;
       public         heap    postgres    false            �            1259    18799    jobs    TABLE     �   CREATE TABLE public.jobs (
    id bigint NOT NULL,
    queue character varying(255) NOT NULL,
    payload text NOT NULL,
    attempts smallint NOT NULL,
    reserved_at integer,
    available_at integer NOT NULL,
    created_at integer NOT NULL
);
    DROP TABLE public.jobs;
       public         heap    postgres    false            �            1259    18798    jobs_id_seq    SEQUENCE     t   CREATE SEQUENCE public.jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 "   DROP SEQUENCE public.jobs_id_seq;
       public          postgres    false    220            �           0    0    jobs_id_seq    SEQUENCE OWNED BY     ;   ALTER SEQUENCE public.jobs_id_seq OWNED BY public.jobs.id;
          public          postgres    false    219            �            1259    18778 
   migrations    TABLE     �   CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);
    DROP TABLE public.migrations;
       public         heap    postgres    false            �            1259    18777    migrations_id_seq    SEQUENCE     �   CREATE SEQUENCE public.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 (   DROP SEQUENCE public.migrations_id_seq;
       public          postgres    false    216            �           0    0    migrations_id_seq    SEQUENCE OWNED BY     G   ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;
          public          postgres    false    215            �            1259    18919    model_has_permissions    TABLE     �   CREATE TABLE public.model_has_permissions (
    permission_id bigint NOT NULL,
    model_type character varying(255) NOT NULL,
    model_id bigint NOT NULL
);
 )   DROP TABLE public.model_has_permissions;
       public         heap    postgres    false            �            1259    18930    model_has_roles    TABLE     �   CREATE TABLE public.model_has_roles (
    role_id bigint NOT NULL,
    model_type character varying(255) NOT NULL,
    model_id bigint NOT NULL
);
 #   DROP TABLE public.model_has_roles;
       public         heap    postgres    false            �            1259    18909    permissions    TABLE     �   CREATE TABLE public.permissions (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    guard_name character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
    DROP TABLE public.permissions;
       public         heap    postgres    false            �            1259    18908    permissions_id_seq    SEQUENCE     {   CREATE SEQUENCE public.permissions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 )   DROP SEQUENCE public.permissions_id_seq;
       public          postgres    false    235            �           0    0    permissions_id_seq    SEQUENCE OWNED BY     I   ALTER SEQUENCE public.permissions_id_seq OWNED BY public.permissions.id;
          public          postgres    false    234            �            1259    18970    reviews    TABLE     �   CREATE TABLE public.reviews (
    id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
    DROP TABLE public.reviews;
       public         heap    postgres    false            �            1259    18969    reviews_id_seq    SEQUENCE     w   CREATE SEQUENCE public.reviews_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 %   DROP SEQUENCE public.reviews_id_seq;
       public          postgres    false    242            �           0    0    reviews_id_seq    SEQUENCE OWNED BY     A   ALTER SEQUENCE public.reviews_id_seq OWNED BY public.reviews.id;
          public          postgres    false    241            �            1259    18828    roles    TABLE       CREATE TABLE public.roles (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    guard_name character varying(255) DEFAULT 'web'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
    DROP TABLE public.roles;
       public         heap    postgres    false            �            1259    18827    roles_id_seq    SEQUENCE     u   CREATE SEQUENCE public.roles_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 #   DROP SEQUENCE public.roles_id_seq;
       public          postgres    false    225            �           0    0    roles_id_seq    SEQUENCE OWNED BY     =   ALTER SEQUENCE public.roles_id_seq OWNED BY public.roles.id;
          public          postgres    false    224            �            1259    18941    sessions    TABLE     �   CREATE TABLE public.sessions (
    id character varying(255) NOT NULL,
    user_id bigint,
    ip_address character varying(45),
    user_agent text,
    payload text NOT NULL,
    last_activity integer NOT NULL
);
    DROP TABLE public.sessions;
       public         heap    postgres    false            �            1259    18840    users    TABLE     B  CREATE TABLE public.users (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    password character varying(255) NOT NULL,
    remember_token character varying(100),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
    DROP TABLE public.users;
       public         heap    postgres    false            �            1259    18839    users_id_seq    SEQUENCE     u   CREATE SEQUENCE public.users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 #   DROP SEQUENCE public.users_id_seq;
       public          postgres    false    227            �           0    0    users_id_seq    SEQUENCE OWNED BY     =   ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;
          public          postgres    false    226            �           2604    18888    comments id    DEFAULT     j   ALTER TABLE ONLY public.comments ALTER COLUMN id SET DEFAULT nextval('public.comments_id_seq'::regclass);
 :   ALTER TABLE public.comments ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    232    233    233            �           2604    18874    course_contents id    DEFAULT     x   ALTER TABLE ONLY public.course_contents ALTER COLUMN id SET DEFAULT nextval('public.course_contents_id_seq'::regclass);
 A   ALTER TABLE public.course_contents ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    231    230    231            �           2604    18954    course_user id    DEFAULT     p   ALTER TABLE ONLY public.course_user ALTER COLUMN id SET DEFAULT nextval('public.course_user_id_seq'::regclass);
 =   ALTER TABLE public.course_user ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    239    240    240            �           2604    18859 
   courses id    DEFAULT     h   ALTER TABLE ONLY public.courses ALTER COLUMN id SET DEFAULT nextval('public.courses_id_seq'::regclass);
 9   ALTER TABLE public.courses ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    229    228    229            �           2604    18819    failed_jobs id    DEFAULT     p   ALTER TABLE ONLY public.failed_jobs ALTER COLUMN id SET DEFAULT nextval('public.failed_jobs_id_seq'::regclass);
 =   ALTER TABLE public.failed_jobs ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    222    223    223            �           2604    18802    jobs id    DEFAULT     b   ALTER TABLE ONLY public.jobs ALTER COLUMN id SET DEFAULT nextval('public.jobs_id_seq'::regclass);
 6   ALTER TABLE public.jobs ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    220    219    220            �           2604    18781    migrations id    DEFAULT     n   ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);
 <   ALTER TABLE public.migrations ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    215    216    216            �           2604    18912    permissions id    DEFAULT     p   ALTER TABLE ONLY public.permissions ALTER COLUMN id SET DEFAULT nextval('public.permissions_id_seq'::regclass);
 =   ALTER TABLE public.permissions ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    235    234    235            �           2604    18973 
   reviews id    DEFAULT     h   ALTER TABLE ONLY public.reviews ALTER COLUMN id SET DEFAULT nextval('public.reviews_id_seq'::regclass);
 9   ALTER TABLE public.reviews ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    242    241    242            �           2604    18831    roles id    DEFAULT     d   ALTER TABLE ONLY public.roles ALTER COLUMN id SET DEFAULT nextval('public.roles_id_seq'::regclass);
 7   ALTER TABLE public.roles ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    225    224    225            �           2604    18843    users id    DEFAULT     d   ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);
 7   ALTER TABLE public.users ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    226    227    227            r          0    18784    cache 
   TABLE DATA           7   COPY public.cache (key, value, expiration) FROM stdin;
    public          postgres    false    217   ӊ       s          0    18791    cache_locks 
   TABLE DATA           =   COPY public.cache_locks (key, owner, expiration) FROM stdin;
    public          postgres    false    218   ��       �          0    18885    comments 
   TABLE DATA           f   COPY public.comments (id, course_id, user_id, content, parent_id, created_at, updated_at) FROM stdin;
    public          postgres    false    233   �       �          0    18871    course_contents 
   TABLE DATA           p   COPY public.course_contents (id, course_id, content, content_type, created_at, updated_at, "order") FROM stdin;
    public          postgres    false    231   x�       �          0    18951    course_user 
   TABLE DATA           U   COPY public.course_user (id, course_id, user_id, created_at, updated_at) FROM stdin;
    public          postgres    false    240   H�       ~          0    18856    courses 
   TABLE DATA           m   COPY public.courses (id, title, description, thumbnail, status, user_id, created_at, updated_at) FROM stdin;
    public          postgres    false    229   ��       x          0    18816    failed_jobs 
   TABLE DATA           a   COPY public.failed_jobs (id, uuid, connection, queue, payload, exception, failed_at) FROM stdin;
    public          postgres    false    223   ��       v          0    18808    job_batches 
   TABLE DATA           �   COPY public.job_batches (id, name, total_jobs, pending_jobs, failed_jobs, failed_job_ids, options, cancelled_at, created_at, finished_at) FROM stdin;
    public          postgres    false    221   ޑ       u          0    18799    jobs 
   TABLE DATA           c   COPY public.jobs (id, queue, payload, attempts, reserved_at, available_at, created_at) FROM stdin;
    public          postgres    false    220   ��       q          0    18778 
   migrations 
   TABLE DATA           :   COPY public.migrations (id, migration, batch) FROM stdin;
    public          postgres    false    216   �       �          0    18919    model_has_permissions 
   TABLE DATA           T   COPY public.model_has_permissions (permission_id, model_type, model_id) FROM stdin;
    public          postgres    false    236    �       �          0    18930    model_has_roles 
   TABLE DATA           H   COPY public.model_has_roles (role_id, model_type, model_id) FROM stdin;
    public          postgres    false    237   �       �          0    18909    permissions 
   TABLE DATA           S   COPY public.permissions (id, name, guard_name, created_at, updated_at) FROM stdin;
    public          postgres    false    235   b�       �          0    18970    reviews 
   TABLE DATA           =   COPY public.reviews (id, created_at, updated_at) FROM stdin;
    public          postgres    false    242   �       z          0    18828    roles 
   TABLE DATA           M   COPY public.roles (id, name, guard_name, created_at, updated_at) FROM stdin;
    public          postgres    false    225   ��       �          0    18941    sessions 
   TABLE DATA           _   COPY public.sessions (id, user_id, ip_address, user_agent, payload, last_activity) FROM stdin;
    public          postgres    false    238   �       |          0    18840    users 
   TABLE DATA           b   COPY public.users (id, name, email, password, remember_token, created_at, updated_at) FROM stdin;
    public          postgres    false    227   ��       �           0    0    comments_id_seq    SEQUENCE SET     =   SELECT pg_catalog.setval('public.comments_id_seq', 3, true);
          public          postgres    false    232            �           0    0    course_contents_id_seq    SEQUENCE SET     D   SELECT pg_catalog.setval('public.course_contents_id_seq', 5, true);
          public          postgres    false    230            �           0    0    course_user_id_seq    SEQUENCE SET     @   SELECT pg_catalog.setval('public.course_user_id_seq', 9, true);
          public          postgres    false    239            �           0    0    courses_id_seq    SEQUENCE SET     <   SELECT pg_catalog.setval('public.courses_id_seq', 9, true);
          public          postgres    false    228            �           0    0    failed_jobs_id_seq    SEQUENCE SET     A   SELECT pg_catalog.setval('public.failed_jobs_id_seq', 1, false);
          public          postgres    false    222            �           0    0    jobs_id_seq    SEQUENCE SET     :   SELECT pg_catalog.setval('public.jobs_id_seq', 1, false);
          public          postgres    false    219            �           0    0    migrations_id_seq    SEQUENCE SET     @   SELECT pg_catalog.setval('public.migrations_id_seq', 13, true);
          public          postgres    false    215            �           0    0    permissions_id_seq    SEQUENCE SET     A   SELECT pg_catalog.setval('public.permissions_id_seq', 1, false);
          public          postgres    false    234            �           0    0    reviews_id_seq    SEQUENCE SET     =   SELECT pg_catalog.setval('public.reviews_id_seq', 1, false);
          public          postgres    false    241            �           0    0    roles_id_seq    SEQUENCE SET     ;   SELECT pg_catalog.setval('public.roles_id_seq', 1, false);
          public          postgres    false    224            �           0    0    users_id_seq    SEQUENCE SET     :   SELECT pg_catalog.setval('public.users_id_seq', 9, true);
          public          postgres    false    226            �           2606    18797    cache_locks cache_locks_pkey 
   CONSTRAINT     [   ALTER TABLE ONLY public.cache_locks
    ADD CONSTRAINT cache_locks_pkey PRIMARY KEY (key);
 F   ALTER TABLE ONLY public.cache_locks DROP CONSTRAINT cache_locks_pkey;
       public            postgres    false    218            �           2606    18790    cache cache_pkey 
   CONSTRAINT     O   ALTER TABLE ONLY public.cache
    ADD CONSTRAINT cache_pkey PRIMARY KEY (key);
 :   ALTER TABLE ONLY public.cache DROP CONSTRAINT cache_pkey;
       public            postgres    false    217            �           2606    18892    comments comments_pkey 
   CONSTRAINT     T   ALTER TABLE ONLY public.comments
    ADD CONSTRAINT comments_pkey PRIMARY KEY (id);
 @   ALTER TABLE ONLY public.comments DROP CONSTRAINT comments_pkey;
       public            postgres    false    233            �           2606    18878 $   course_contents course_contents_pkey 
   CONSTRAINT     b   ALTER TABLE ONLY public.course_contents
    ADD CONSTRAINT course_contents_pkey PRIMARY KEY (id);
 N   ALTER TABLE ONLY public.course_contents DROP CONSTRAINT course_contents_pkey;
       public            postgres    false    231            �           2606    18956    course_user course_user_pkey 
   CONSTRAINT     Z   ALTER TABLE ONLY public.course_user
    ADD CONSTRAINT course_user_pkey PRIMARY KEY (id);
 F   ALTER TABLE ONLY public.course_user DROP CONSTRAINT course_user_pkey;
       public            postgres    false    240            �           2606    18864    courses courses_pkey 
   CONSTRAINT     R   ALTER TABLE ONLY public.courses
    ADD CONSTRAINT courses_pkey PRIMARY KEY (id);
 >   ALTER TABLE ONLY public.courses DROP CONSTRAINT courses_pkey;
       public            postgres    false    229            �           2606    18824    failed_jobs failed_jobs_pkey 
   CONSTRAINT     Z   ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_pkey PRIMARY KEY (id);
 F   ALTER TABLE ONLY public.failed_jobs DROP CONSTRAINT failed_jobs_pkey;
       public            postgres    false    223            �           2606    18826 #   failed_jobs failed_jobs_uuid_unique 
   CONSTRAINT     ^   ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_uuid_unique UNIQUE (uuid);
 M   ALTER TABLE ONLY public.failed_jobs DROP CONSTRAINT failed_jobs_uuid_unique;
       public            postgres    false    223            �           2606    18814    job_batches job_batches_pkey 
   CONSTRAINT     Z   ALTER TABLE ONLY public.job_batches
    ADD CONSTRAINT job_batches_pkey PRIMARY KEY (id);
 F   ALTER TABLE ONLY public.job_batches DROP CONSTRAINT job_batches_pkey;
       public            postgres    false    221            �           2606    18806    jobs jobs_pkey 
   CONSTRAINT     L   ALTER TABLE ONLY public.jobs
    ADD CONSTRAINT jobs_pkey PRIMARY KEY (id);
 8   ALTER TABLE ONLY public.jobs DROP CONSTRAINT jobs_pkey;
       public            postgres    false    220            �           2606    18783    migrations migrations_pkey 
   CONSTRAINT     X   ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);
 D   ALTER TABLE ONLY public.migrations DROP CONSTRAINT migrations_pkey;
       public            postgres    false    216            �           2606    18929 0   model_has_permissions model_has_permissions_pkey 
   CONSTRAINT     �   ALTER TABLE ONLY public.model_has_permissions
    ADD CONSTRAINT model_has_permissions_pkey PRIMARY KEY (permission_id, model_id, model_type);
 Z   ALTER TABLE ONLY public.model_has_permissions DROP CONSTRAINT model_has_permissions_pkey;
       public            postgres    false    236    236    236            �           2606    18940 $   model_has_roles model_has_roles_pkey 
   CONSTRAINT     }   ALTER TABLE ONLY public.model_has_roles
    ADD CONSTRAINT model_has_roles_pkey PRIMARY KEY (role_id, model_id, model_type);
 N   ALTER TABLE ONLY public.model_has_roles DROP CONSTRAINT model_has_roles_pkey;
       public            postgres    false    237    237    237            �           2606    18918 .   permissions permissions_name_guard_name_unique 
   CONSTRAINT     u   ALTER TABLE ONLY public.permissions
    ADD CONSTRAINT permissions_name_guard_name_unique UNIQUE (name, guard_name);
 X   ALTER TABLE ONLY public.permissions DROP CONSTRAINT permissions_name_guard_name_unique;
       public            postgres    false    235    235            �           2606    18916    permissions permissions_pkey 
   CONSTRAINT     Z   ALTER TABLE ONLY public.permissions
    ADD CONSTRAINT permissions_pkey PRIMARY KEY (id);
 F   ALTER TABLE ONLY public.permissions DROP CONSTRAINT permissions_pkey;
       public            postgres    false    235            �           2606    18975    reviews reviews_pkey 
   CONSTRAINT     R   ALTER TABLE ONLY public.reviews
    ADD CONSTRAINT reviews_pkey PRIMARY KEY (id);
 >   ALTER TABLE ONLY public.reviews DROP CONSTRAINT reviews_pkey;
       public            postgres    false    242            �           2606    18838    roles roles_name_unique 
   CONSTRAINT     R   ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_name_unique UNIQUE (name);
 A   ALTER TABLE ONLY public.roles DROP CONSTRAINT roles_name_unique;
       public            postgres    false    225            �           2606    18836    roles roles_pkey 
   CONSTRAINT     N   ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_pkey PRIMARY KEY (id);
 :   ALTER TABLE ONLY public.roles DROP CONSTRAINT roles_pkey;
       public            postgres    false    225            �           2606    18947    sessions sessions_pkey 
   CONSTRAINT     T   ALTER TABLE ONLY public.sessions
    ADD CONSTRAINT sessions_pkey PRIMARY KEY (id);
 @   ALTER TABLE ONLY public.sessions DROP CONSTRAINT sessions_pkey;
       public            postgres    false    238            �           2606    18854    users users_email_unique 
   CONSTRAINT     T   ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_unique UNIQUE (email);
 B   ALTER TABLE ONLY public.users DROP CONSTRAINT users_email_unique;
       public            postgres    false    227            �           2606    18847    users users_pkey 
   CONSTRAINT     N   ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);
 :   ALTER TABLE ONLY public.users DROP CONSTRAINT users_pkey;
       public            postgres    false    227            �           1259    18807    jobs_queue_index    INDEX     B   CREATE INDEX jobs_queue_index ON public.jobs USING btree (queue);
 $   DROP INDEX public.jobs_queue_index;
       public            postgres    false    220            �           1259    18922 /   model_has_permissions_model_id_model_type_index    INDEX     �   CREATE INDEX model_has_permissions_model_id_model_type_index ON public.model_has_permissions USING btree (model_id, model_type);
 C   DROP INDEX public.model_has_permissions_model_id_model_type_index;
       public            postgres    false    236    236            �           1259    18933 )   model_has_roles_model_id_model_type_index    INDEX     u   CREATE INDEX model_has_roles_model_id_model_type_index ON public.model_has_roles USING btree (model_id, model_type);
 =   DROP INDEX public.model_has_roles_model_id_model_type_index;
       public            postgres    false    237    237            �           1259    18949    sessions_last_activity_index    INDEX     Z   CREATE INDEX sessions_last_activity_index ON public.sessions USING btree (last_activity);
 0   DROP INDEX public.sessions_last_activity_index;
       public            postgres    false    238            �           1259    18948    sessions_user_id_index    INDEX     N   CREATE INDEX sessions_user_id_index ON public.sessions USING btree (user_id);
 *   DROP INDEX public.sessions_user_id_index;
       public            postgres    false    238            �           2606    18893 #   comments comments_course_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY public.comments
    ADD CONSTRAINT comments_course_id_foreign FOREIGN KEY (course_id) REFERENCES public.courses(id) ON DELETE CASCADE;
 M   ALTER TABLE ONLY public.comments DROP CONSTRAINT comments_course_id_foreign;
       public          postgres    false    4801    233    229            �           2606    18903 #   comments comments_parent_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY public.comments
    ADD CONSTRAINT comments_parent_id_foreign FOREIGN KEY (parent_id) REFERENCES public.comments(id) ON DELETE CASCADE;
 M   ALTER TABLE ONLY public.comments DROP CONSTRAINT comments_parent_id_foreign;
       public          postgres    false    4805    233    233            �           2606    18898 !   comments comments_user_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY public.comments
    ADD CONSTRAINT comments_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;
 K   ALTER TABLE ONLY public.comments DROP CONSTRAINT comments_user_id_foreign;
       public          postgres    false    233    4799    227            �           2606    18879 1   course_contents course_contents_course_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY public.course_contents
    ADD CONSTRAINT course_contents_course_id_foreign FOREIGN KEY (course_id) REFERENCES public.courses(id) ON DELETE CASCADE;
 [   ALTER TABLE ONLY public.course_contents DROP CONSTRAINT course_contents_course_id_foreign;
       public          postgres    false    229    231    4801            �           2606    18957 )   course_user course_user_course_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY public.course_user
    ADD CONSTRAINT course_user_course_id_foreign FOREIGN KEY (course_id) REFERENCES public.courses(id) ON DELETE CASCADE;
 S   ALTER TABLE ONLY public.course_user DROP CONSTRAINT course_user_course_id_foreign;
       public          postgres    false    4801    229    240            �           2606    18962 '   course_user course_user_user_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY public.course_user
    ADD CONSTRAINT course_user_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;
 Q   ALTER TABLE ONLY public.course_user DROP CONSTRAINT course_user_user_id_foreign;
       public          postgres    false    4799    227    240            �           2606    18865    courses courses_user_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY public.courses
    ADD CONSTRAINT courses_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;
 I   ALTER TABLE ONLY public.courses DROP CONSTRAINT courses_user_id_foreign;
       public          postgres    false    227    229    4799            �           2606    18923 A   model_has_permissions model_has_permissions_permission_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY public.model_has_permissions
    ADD CONSTRAINT model_has_permissions_permission_id_foreign FOREIGN KEY (permission_id) REFERENCES public.permissions(id) ON DELETE CASCADE;
 k   ALTER TABLE ONLY public.model_has_permissions DROP CONSTRAINT model_has_permissions_permission_id_foreign;
       public          postgres    false    236    4809    235            �           2606    18934 /   model_has_roles model_has_roles_role_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY public.model_has_roles
    ADD CONSTRAINT model_has_roles_role_id_foreign FOREIGN KEY (role_id) REFERENCES public.roles(id) ON DELETE CASCADE;
 Y   ALTER TABLE ONLY public.model_has_roles DROP CONSTRAINT model_has_roles_role_id_foreign;
       public          postgres    false    4795    237    225            r      x������ � �      s      x������ � �      �   [   x�3�4�4��H�����4202�50�52Q04�26�20�&�e�U�Z\��XXc�2���I-.A��T�����h261�=... �� /      �   �  x��T�N�@}��b�x Al�!M1�(	��J�^$��8^���k�h�יִ8��%R|��9s��I\�J��m�f3�%UR����=C9{O��S���H�P��'��p��p:��-��xNgk�1[F�Ȃ�������G7�z��2�Cy�����Gy������hhQ����k�<��5�GƻWgxq�|�y�χ�-3t�v�k�[c��6������L��d,W1�	
x���@��D�(�
�!�;Zf^��F�{�c�;O�ma*f8u��Q/LT�5a���oQ!��BbN��%�UҒ����[���(�1�q��d�i�QH�����W!��(L��Pq񂇐`�+1|���H�4���Z�7-��@Ѻ�4Ϧ�5��<9��*i�T=U�:��	�,�,0_m����J��,�/t {�N�9�w�Y>����@�?NI�4`ym�P��7Up������ޙ�~#)�g�F������Y��Ĥ r��\���7r��EY��qb+��D��*��h�0�cݚ6*����uq�0�ɂ��J��cu�Jzm��H�����Cr��UϦcA?���VPH��	��)-��gk�c�
TXz'3��t$&�Uh��`�cV	���tͲy>L�L���5W�q��N7tϮi���"���]�vN6<a �1�������5�=��5��i� HK�       �   0   x�3�4�4��".cN8ۄ��6崄�-��-�lK�z;F��� %w      ~   )  x��T�r�H}��������ŊAdI9vB�����PyiY�4�4#F#����)�/lX���z�r���Y�,�%*�EʔuH���Xd��k���=8t��x�;��������;/�+�j�0f�-+51�C2�Y�5���LhK'U
�Y���Ŵ��f������~��xZ}��,~���>���p���u�E��A�;��nl>�.��~g`�d���ȭ�+.R���8�7S�����%��ë[���8뿿��+?�t�ye_�B�+8^�y����q���;�Z�'k��0k�L�
��E�V�@�K��k�����ԝ0����\�=�<�۳�e���p�밠Z�WM՜	bz�.x�@�2���AkFt��n��[�e��'D�aY�
"Ȥ,�$�ۦ��G�)� �LcRq���Q�,�$.�+��~��.�� ��3�g���ψ����DZ�V�^V�n>�:��(gI��*�E�v��'ER�_h�=��;د��cp�/��m��~���$ze��F"4�:��T��͸���{���gD%�,3��>�]rB��T".)� F�@�j��$\WF�La-����ٮ���_�δ��0�mS�&*d�����,���8n\*�|W�U۠w:/�;�q���'��Ϛ��݅�ҍ���]{���[J1���ǐ�U��23Y���� GcNZ������� ��j6���椔ɣ��?U�E"��q⾹�n��K��S~ܣ�'��4�}��?��S�g����K�6���ru3���ӏ3���]:z�l��Wt��l����n���<0+�      x      x������ � �      v      x������ � �      u      x������ � �      q   �   x�u��� ���a�$���� tm�u�:�۽���k=+A�_�D�  �-��tQ�I�k���ԞoҠ�
�
��6�����c�I���[�#I��VPo-�GL�25%��q�q�%�*�����K`������ƗN3?�����M/6G#��Ow�/��=�޸���=��/,�}0RQy�dG����=��wS�b�h��n�<�^-�����ŷ�      �      x������ � �      �   5   x�3�t,(����OI�)��	-N-�4�2��"ġ2�0�\FXD-�b���� !}      �      x������ � �      �      x������ � �      z   B   x�3�LL����,OM�4202�50�52Q04�2��22�&�e�YZ�ZD�c���Ē|�t��qqq gR"�      �   �  x�ՕKo�H�����8�V	���l�'����yi.���>�4q2�vG���Z��TEWU��_��A��C��֙�:�$��l9�`ڡ�s�.m899#4pr���p�'y�=�{��f%eX�'F1@ݏ5�#s�;fV�ydE�&9? nr�	̷����ߘ<�"F����c��cUD���f`������ے�cT�Z�{5u�u.�><�~��}��y�(`y�	R	��pY�*�-sQdhW��K�5K�ϐ���c�UZ6��u=Ry��<,�&�q���z�����MTS�]�l��	�nz�ip��|�M��k+|i�{��	���Ռ�:���l-��o�/�������b���O>k?9ג���'���c���	.��gW�xiݔ~%̹)�]k��nU��<�*��1��@��0�l�r�n�ռȜ��IYF����1;rp��Y�Gs4��`3�����K�p5oYw�����b�n���
��L|.ĮT�$=�DO�+�{�����_�o(��g�;`��Ʊ�l��"�C|���|��N�n|I��~�Q%~artm��3!��{�%��,����=AN��b���T.TCk�Ax,9���
t�*��"��3^��\�������O3���YG�U6�!z
P��3/��r��j}y����/������ٻ�����Y&�h_]^��$L�k�zbehI:��3��06�X��'���%"@U$8F�A���_A�ė67����L�;��M��� ����=�����f'~�olc{J��w���>�zf�_��d:�WAn�&�e<&���xHϓB_J�*T�B7�n�g6�xj��R�饐��)��J�������N�f�މ�+��O�̱��)�8(�7��Po&�,��p����������Ŀi��wПB���{r�4��|�%�f�t�v��<�>.�����M?�~?� Z�~�      |   T  x�m�9s�@ �k������Q�D�(���86�A9��¯���D�7o���Q"/!��'�M��)��q�&���dt	?��8d:��@��j�J��\	nOٻW��E���6\�`:���k��*��s4����2e����5I3����߻H�B/���n�5��L]��K��#�Ȳ���å:�lO\���.ɠ��X�Q��\��x�k�I�w���\S�Z�p��֮�����*�B_�h=7�Ц^`������� �1V�]�G�MJ�O4����j��{���H�ɾ�fZ@�>��q��KC�xÓA�Q�\AJ--�"�HF��{�Y��i��     