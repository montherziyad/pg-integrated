--
-- PostgreSQL database dump
--

\restrict HfI4JExe45Se6WiEDph693jT8gKBZciLIEtRn6YPpPdJifEoZYB3FLhQU3w24sK

-- Dumped from database version 18.4 (Homebrew)
-- Dumped by pg_dump version 18.4 (Homebrew)

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: ai_approval_suggestions; Type: TABLE; Schema: public; Owner: mziyad
--

CREATE TABLE public.ai_approval_suggestions (
    id bigint NOT NULL,
    type character varying(255) NOT NULL,
    title character varying(255) NOT NULL,
    summary text,
    subject_type character varying(255) NOT NULL,
    subject_id bigint NOT NULL,
    payload json NOT NULL,
    status character varying(255) DEFAULT 'pending'::character varying NOT NULL,
    created_by bigint,
    approved_by bigint,
    approved_at timestamp(0) without time zone,
    rejected_at timestamp(0) without time zone,
    decision_notes text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.ai_approval_suggestions OWNER TO mziyad;

--
-- Name: ai_approval_suggestions_id_seq; Type: SEQUENCE; Schema: public; Owner: mziyad
--

CREATE SEQUENCE public.ai_approval_suggestions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ai_approval_suggestions_id_seq OWNER TO mziyad;

--
-- Name: ai_approval_suggestions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: mziyad
--

ALTER SEQUENCE public.ai_approval_suggestions_id_seq OWNED BY public.ai_approval_suggestions.id;


--
-- Name: ai_interactions; Type: TABLE; Schema: public; Owner: mziyad
--

CREATE TABLE public.ai_interactions (
    id bigint NOT NULL,
    provider character varying(255) DEFAULT 'openai'::character varying NOT NULL,
    agent character varying(255) DEFAULT 'sales_assistant'::character varying NOT NULL,
    subject_type character varying(255) NOT NULL,
    subject_id bigint NOT NULL,
    user_id bigint,
    prompt text NOT NULL,
    response text,
    meta json,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.ai_interactions OWNER TO mziyad;

--
-- Name: ai_interactions_id_seq; Type: SEQUENCE; Schema: public; Owner: mziyad
--

CREATE SEQUENCE public.ai_interactions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ai_interactions_id_seq OWNER TO mziyad;

--
-- Name: ai_interactions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: mziyad
--

ALTER SEQUENCE public.ai_interactions_id_seq OWNED BY public.ai_interactions.id;


--
-- Name: assets; Type: TABLE; Schema: public; Owner: mziyad
--

CREATE TABLE public.assets (
    id bigint NOT NULL,
    creative_job_id bigint NOT NULL,
    uploaded_by bigint,
    file_name character varying(255) NOT NULL,
    original_name character varying(255),
    file_type character varying(255),
    mime_type character varying(255),
    file_size bigint DEFAULT '0'::bigint NOT NULL,
    storage_type character varying(255) DEFAULT 'local'::character varying NOT NULL,
    storage_path text NOT NULL,
    version integer DEFAULT 1 NOT NULL,
    asset_stage character varying(255) DEFAULT 'DESIGN'::character varying NOT NULL,
    is_final boolean DEFAULT false NOT NULL,
    notes text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT assets_asset_stage_check CHECK (((asset_stage)::text = ANY ((ARRAY['BRIEF'::character varying, 'CONTENT'::character varying, 'DESIGN'::character varying, 'MOTION'::character varying, 'REVIEW'::character varying, 'FINAL'::character varying, 'SOURCE'::character varying, 'ARCHIVE'::character varying])::text[])))
);


ALTER TABLE public.assets OWNER TO mziyad;

--
-- Name: assets_id_seq; Type: SEQUENCE; Schema: public; Owner: mziyad
--

CREATE SEQUENCE public.assets_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.assets_id_seq OWNER TO mziyad;

--
-- Name: assets_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: mziyad
--

ALTER SEQUENCE public.assets_id_seq OWNED BY public.assets.id;


--
-- Name: branches; Type: TABLE; Schema: public; Owner: mziyad
--

CREATE TABLE public.branches (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    code character varying(255) NOT NULL,
    country character varying(255),
    city character varying(255),
    is_active boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.branches OWNER TO mziyad;

--
-- Name: branches_id_seq; Type: SEQUENCE; Schema: public; Owner: mziyad
--

CREATE SEQUENCE public.branches_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.branches_id_seq OWNER TO mziyad;

--
-- Name: branches_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: mziyad
--

ALTER SEQUENCE public.branches_id_seq OWNED BY public.branches.id;


--
-- Name: cache; Type: TABLE; Schema: public; Owner: mziyad
--

CREATE TABLE public.cache (
    key character varying(255) NOT NULL,
    value text NOT NULL,
    expiration bigint NOT NULL
);


ALTER TABLE public.cache OWNER TO mziyad;

--
-- Name: cache_locks; Type: TABLE; Schema: public; Owner: mziyad
--

CREATE TABLE public.cache_locks (
    key character varying(255) NOT NULL,
    owner character varying(255) NOT NULL,
    expiration bigint NOT NULL
);


ALTER TABLE public.cache_locks OWNER TO mziyad;

--
-- Name: client_project_requests; Type: TABLE; Schema: public; Owner: mziyad
--

CREATE TABLE public.client_project_requests (
    id bigint NOT NULL,
    request_number character varying(255) NOT NULL,
    client_id bigint NOT NULL,
    project_id bigint,
    type character varying(255) DEFAULT 'project'::character varying NOT NULL,
    title character varying(255) NOT NULL,
    brief text,
    target_country character varying(255),
    desired_launch_date date,
    budget_range character varying(255),
    priority character varying(255) DEFAULT 'normal'::character varying NOT NULL,
    status character varying(255) DEFAULT 'new'::character varying NOT NULL,
    deliverables json,
    attachments json,
    external_links json,
    admin_notes text,
    reviewed_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.client_project_requests OWNER TO mziyad;

--
-- Name: client_project_requests_id_seq; Type: SEQUENCE; Schema: public; Owner: mziyad
--

CREATE SEQUENCE public.client_project_requests_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.client_project_requests_id_seq OWNER TO mziyad;

--
-- Name: client_project_requests_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: mziyad
--

ALTER SEQUENCE public.client_project_requests_id_seq OWNED BY public.client_project_requests.id;


--
-- Name: clients; Type: TABLE; Schema: public; Owner: mziyad
--

CREATE TABLE public.clients (
    id bigint NOT NULL,
    client_code character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    branch_id bigint,
    account_manager_id bigint,
    industry character varying(255),
    email character varying(255),
    phone character varying(255),
    is_active boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    password character varying(255),
    remember_token character varying(100),
    portal_enabled boolean DEFAULT false NOT NULL,
    last_login_at timestamp(0) without time zone,
    company_name character varying(255),
    contact_person character varying(255),
    country character varying(255),
    city character varying(255),
    website character varying(255),
    avatar_path character varying(255),
    company_profile text
);


ALTER TABLE public.clients OWNER TO mziyad;

--
-- Name: clients_id_seq; Type: SEQUENCE; Schema: public; Owner: mziyad
--

CREATE SEQUENCE public.clients_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.clients_id_seq OWNER TO mziyad;

--
-- Name: clients_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: mziyad
--

ALTER SEQUENCE public.clients_id_seq OWNED BY public.clients.id;


--
-- Name: cms_pages; Type: TABLE; Schema: public; Owner: mziyad
--

CREATE TABLE public.cms_pages (
    id bigint NOT NULL,
    key character varying(255) NOT NULL,
    title character varying(255) NOT NULL,
    slug character varying(255) NOT NULL,
    sections json,
    seo json,
    is_published boolean DEFAULT true NOT NULL,
    updated_by bigint,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.cms_pages OWNER TO mziyad;

--
-- Name: cms_pages_id_seq; Type: SEQUENCE; Schema: public; Owner: mziyad
--

CREATE SEQUENCE public.cms_pages_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.cms_pages_id_seq OWNER TO mziyad;

--
-- Name: cms_pages_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: mziyad
--

ALTER SEQUENCE public.cms_pages_id_seq OWNED BY public.cms_pages.id;


--
-- Name: creative_jobs; Type: TABLE; Schema: public; Owner: mziyad
--

CREATE TABLE public.creative_jobs (
    id bigint NOT NULL,
    job_number character varying(255) NOT NULL,
    client_id bigint NOT NULL,
    project_id bigint,
    job_category_id bigint,
    job_status_id bigint,
    traffic_manager_id bigint,
    project_manager_id bigint,
    title character varying(255) NOT NULL,
    brief text,
    priority character varying(255) DEFAULT 'MEDIUM'::character varying NOT NULL,
    received_at timestamp(0) without time zone,
    first_draft_due_at timestamp(0) without time zone,
    final_due_at timestamp(0) without time zone,
    first_draft_sent_at timestamp(0) without time zone,
    final_delivered_at timestamp(0) without time zone,
    estimated_hours numeric(8,2) DEFAULT '0'::numeric NOT NULL,
    actual_hours numeric(8,2) DEFAULT '0'::numeric NOT NULL,
    revision_count integer DEFAULT 0 NOT NULL,
    reopened_count integer DEFAULT 0 NOT NULL,
    completion_percentage integer DEFAULT 0 NOT NULL,
    internal_notes text,
    client_notes text,
    nas_folder_path character varying(255),
    dropbox_folder_path character varying(255),
    final_delivery_path character varying(255),
    is_archived boolean DEFAULT false NOT NULL,
    archived_at timestamp(0) without time zone,
    created_by bigint,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    current_workflow_stage_id bigint,
    CONSTRAINT creative_jobs_priority_check CHECK (((priority)::text = ANY ((ARRAY['LOW'::character varying, 'MEDIUM'::character varying, 'HIGH'::character varying, 'URGENT'::character varying, 'CRITICAL'::character varying])::text[])))
);


ALTER TABLE public.creative_jobs OWNER TO mziyad;

--
-- Name: creative_jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: mziyad
--

CREATE SEQUENCE public.creative_jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.creative_jobs_id_seq OWNER TO mziyad;

--
-- Name: creative_jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: mziyad
--

ALTER SEQUENCE public.creative_jobs_id_seq OWNED BY public.creative_jobs.id;


--
-- Name: crm_activities; Type: TABLE; Schema: public; Owner: mziyad
--

CREATE TABLE public.crm_activities (
    id bigint NOT NULL,
    company_id bigint NOT NULL,
    contact_id bigint,
    user_id bigint,
    type character varying(255) DEFAULT 'note'::character varying NOT NULL,
    channel character varying(255),
    summary text NOT NULL,
    body text,
    activity_at timestamp(0) without time zone,
    meta json,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.crm_activities OWNER TO mziyad;

--
-- Name: crm_activities_id_seq; Type: SEQUENCE; Schema: public; Owner: mziyad
--

CREATE SEQUENCE public.crm_activities_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.crm_activities_id_seq OWNER TO mziyad;

--
-- Name: crm_activities_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: mziyad
--

ALTER SEQUENCE public.crm_activities_id_seq OWNED BY public.crm_activities.id;


--
-- Name: crm_companies; Type: TABLE; Schema: public; Owner: mziyad
--

CREATE TABLE public.crm_companies (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    industry character varying(255),
    country character varying(255),
    website character varying(255),
    linkedin_url character varying(255),
    source character varying(255),
    status character varying(255) DEFAULT 'new'::character varying NOT NULL,
    lead_score smallint DEFAULT '0'::smallint NOT NULL,
    expected_value numeric(12,2),
    owner_id bigint,
    last_contacted_at timestamp(0) without time zone,
    next_follow_up_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.crm_companies OWNER TO mziyad;

--
-- Name: crm_companies_id_seq; Type: SEQUENCE; Schema: public; Owner: mziyad
--

CREATE SEQUENCE public.crm_companies_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.crm_companies_id_seq OWNER TO mziyad;

--
-- Name: crm_companies_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: mziyad
--

ALTER SEQUENCE public.crm_companies_id_seq OWNED BY public.crm_companies.id;


--
-- Name: crm_contacts; Type: TABLE; Schema: public; Owner: mziyad
--

CREATE TABLE public.crm_contacts (
    id bigint NOT NULL,
    company_id bigint NOT NULL,
    name character varying(255) NOT NULL,
    "position" character varying(255),
    email character varying(255),
    phone character varying(255),
    whatsapp character varying(255),
    is_primary boolean DEFAULT false NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.crm_contacts OWNER TO mziyad;

--
-- Name: crm_contacts_id_seq; Type: SEQUENCE; Schema: public; Owner: mziyad
--

CREATE SEQUENCE public.crm_contacts_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.crm_contacts_id_seq OWNER TO mziyad;

--
-- Name: crm_contacts_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: mziyad
--

ALTER SEQUENCE public.crm_contacts_id_seq OWNED BY public.crm_contacts.id;


--
-- Name: crm_tasks; Type: TABLE; Schema: public; Owner: mziyad
--

CREATE TABLE public.crm_tasks (
    id bigint NOT NULL,
    company_id bigint,
    assigned_to bigint,
    title character varying(255) NOT NULL,
    description text,
    status character varying(255) DEFAULT 'open'::character varying NOT NULL,
    due_at timestamp(0) without time zone,
    completed_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.crm_tasks OWNER TO mziyad;

--
-- Name: crm_tasks_id_seq; Type: SEQUENCE; Schema: public; Owner: mziyad
--

CREATE SEQUENCE public.crm_tasks_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.crm_tasks_id_seq OWNER TO mziyad;

--
-- Name: crm_tasks_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: mziyad
--

ALTER SEQUENCE public.crm_tasks_id_seq OWNED BY public.crm_tasks.id;


--
-- Name: email_intake_attachments; Type: TABLE; Schema: public; Owner: mziyad
--

CREATE TABLE public.email_intake_attachments (
    id bigint NOT NULL,
    email_intake_id bigint NOT NULL,
    outlook_attachment_id character varying(255),
    name character varying(255) NOT NULL,
    content_type character varying(255),
    size bigint DEFAULT '0'::bigint NOT NULL,
    is_inline boolean DEFAULT false NOT NULL,
    is_brief boolean DEFAULT false NOT NULL,
    storage_disk character varying(255) DEFAULT 'local'::character varying NOT NULL,
    storage_path text,
    sha256 character varying(64),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.email_intake_attachments OWNER TO mziyad;

--
-- Name: email_intake_attachments_id_seq; Type: SEQUENCE; Schema: public; Owner: mziyad
--

CREATE SEQUENCE public.email_intake_attachments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.email_intake_attachments_id_seq OWNER TO mziyad;

--
-- Name: email_intake_attachments_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: mziyad
--

ALTER SEQUENCE public.email_intake_attachments_id_seq OWNED BY public.email_intake_attachments.id;


--
-- Name: email_intake_traffic_members; Type: TABLE; Schema: public; Owner: mziyad
--

CREATE TABLE public.email_intake_traffic_members (
    id bigint NOT NULL,
    user_id bigint NOT NULL,
    outlook_email character varying(255) NOT NULL,
    is_active boolean DEFAULT true NOT NULL,
    receives_notifications boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.email_intake_traffic_members OWNER TO mziyad;

--
-- Name: email_intake_traffic_members_id_seq; Type: SEQUENCE; Schema: public; Owner: mziyad
--

CREATE SEQUENCE public.email_intake_traffic_members_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.email_intake_traffic_members_id_seq OWNER TO mziyad;

--
-- Name: email_intake_traffic_members_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: mziyad
--

ALTER SEQUENCE public.email_intake_traffic_members_id_seq OWNED BY public.email_intake_traffic_members.id;


--
-- Name: email_intake_validations; Type: TABLE; Schema: public; Owner: mziyad
--

CREATE TABLE public.email_intake_validations (
    id bigint NOT NULL,
    email_intake_id bigint NOT NULL,
    rule character varying(255) NOT NULL,
    passed boolean NOT NULL,
    message text,
    context json,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.email_intake_validations OWNER TO mziyad;

--
-- Name: email_intake_validations_id_seq; Type: SEQUENCE; Schema: public; Owner: mziyad
--

CREATE SEQUENCE public.email_intake_validations_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.email_intake_validations_id_seq OWNER TO mziyad;

--
-- Name: email_intake_validations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: mziyad
--

ALTER SEQUENCE public.email_intake_validations_id_seq OWNED BY public.email_intake_validations.id;


--
-- Name: email_intakes; Type: TABLE; Schema: public; Owner: mziyad
--

CREATE TABLE public.email_intakes (
    id bigint NOT NULL,
    source character varying(255) DEFAULT 'OUTLOOK'::character varying NOT NULL,
    message_id character varying(255) NOT NULL,
    sender_email character varying(255),
    sender_name character varying(255),
    subject character varying(255),
    body text,
    received_at timestamp(0) without time zone,
    has_attachments boolean DEFAULT false NOT NULL,
    status character varying(255) DEFAULT 'NEW'::character varying NOT NULL,
    creative_job_id bigint,
    raw_payload json,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    conversation_id character varying(255),
    internet_message_id character varying(255),
    to_recipients json,
    cc_recipients json,
    extracted_job_number character varying(255),
    validation_passed boolean DEFAULT false NOT NULL,
    validation_errors json,
    rejection_reason text,
    reviewed_by bigint,
    reviewed_at timestamp(0) without time zone,
    accepted_at timestamp(0) without time zone,
    rejected_at timestamp(0) without time zone,
    CONSTRAINT email_intakes_status_check CHECK (((status)::text = ANY ((ARRAY['NEW'::character varying, 'REVIEWED'::character varying, 'CONVERTED_TO_JOB'::character varying, 'IGNORED'::character varying, 'FAILED'::character varying])::text[])))
);


ALTER TABLE public.email_intakes OWNER TO mziyad;

--
-- Name: email_intakes_id_seq; Type: SEQUENCE; Schema: public; Owner: mziyad
--

CREATE SEQUENCE public.email_intakes_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.email_intakes_id_seq OWNER TO mziyad;

--
-- Name: email_intakes_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: mziyad
--

ALTER SEQUENCE public.email_intakes_id_seq OWNED BY public.email_intakes.id;


--
-- Name: failed_jobs; Type: TABLE; Schema: public; Owner: mziyad
--

CREATE TABLE public.failed_jobs (
    id bigint NOT NULL,
    uuid character varying(255) NOT NULL,
    connection character varying(255) NOT NULL,
    queue character varying(255) NOT NULL,
    payload text NOT NULL,
    exception text NOT NULL,
    failed_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


ALTER TABLE public.failed_jobs OWNER TO mziyad;

--
-- Name: failed_jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: mziyad
--

CREATE SEQUENCE public.failed_jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.failed_jobs_id_seq OWNER TO mziyad;

--
-- Name: failed_jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: mziyad
--

ALTER SEQUENCE public.failed_jobs_id_seq OWNED BY public.failed_jobs.id;


--
-- Name: job_activities; Type: TABLE; Schema: public; Owner: mziyad
--

CREATE TABLE public.job_activities (
    id bigint NOT NULL,
    creative_job_id bigint NOT NULL,
    user_id bigint,
    activity character varying(255) NOT NULL,
    activity_type character varying(255) DEFAULT 'SYSTEM'::character varying NOT NULL,
    description text,
    old_values json,
    new_values json,
    ip_address character varying(255),
    device character varying(255),
    activity_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.job_activities OWNER TO mziyad;

--
-- Name: job_activities_id_seq; Type: SEQUENCE; Schema: public; Owner: mziyad
--

CREATE SEQUENCE public.job_activities_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.job_activities_id_seq OWNER TO mziyad;

--
-- Name: job_activities_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: mziyad
--

ALTER SEQUENCE public.job_activities_id_seq OWNED BY public.job_activities.id;


--
-- Name: job_assignments; Type: TABLE; Schema: public; Owner: mziyad
--

CREATE TABLE public.job_assignments (
    id bigint NOT NULL,
    creative_job_id bigint NOT NULL,
    team_id bigint,
    user_id bigint,
    assigned_by bigint,
    assigned_at timestamp(0) without time zone,
    estimated_hours numeric(8,2) DEFAULT '0'::numeric NOT NULL,
    actual_hours numeric(8,2) DEFAULT '0'::numeric NOT NULL,
    started_at timestamp(0) without time zone,
    completed_at timestamp(0) without time zone,
    status character varying(255) DEFAULT 'ASSIGNED'::character varying NOT NULL,
    notes text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    supervisor_id bigint,
    CONSTRAINT job_assignments_status_check CHECK (((status)::text = ANY ((ARRAY['ASSIGNED'::character varying, 'IN_PROGRESS'::character varying, 'COMPLETED'::character varying, 'ON_HOLD'::character varying, 'CANCELLED'::character varying])::text[])))
);


ALTER TABLE public.job_assignments OWNER TO mziyad;

--
-- Name: job_assignments_id_seq; Type: SEQUENCE; Schema: public; Owner: mziyad
--

CREATE SEQUENCE public.job_assignments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.job_assignments_id_seq OWNER TO mziyad;

--
-- Name: job_assignments_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: mziyad
--

ALTER SEQUENCE public.job_assignments_id_seq OWNED BY public.job_assignments.id;


--
-- Name: job_batches; Type: TABLE; Schema: public; Owner: mziyad
--

CREATE TABLE public.job_batches (
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


ALTER TABLE public.job_batches OWNER TO mziyad;

--
-- Name: job_categories; Type: TABLE; Schema: public; Owner: mziyad
--

CREATE TABLE public.job_categories (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    code character varying(255) NOT NULL,
    description text,
    is_active boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.job_categories OWNER TO mziyad;

--
-- Name: job_categories_id_seq; Type: SEQUENCE; Schema: public; Owner: mziyad
--

CREATE SEQUENCE public.job_categories_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.job_categories_id_seq OWNER TO mziyad;

--
-- Name: job_categories_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: mziyad
--

ALTER SEQUENCE public.job_categories_id_seq OWNED BY public.job_categories.id;


--
-- Name: job_stage_histories; Type: TABLE; Schema: public; Owner: mziyad
--

CREATE TABLE public.job_stage_histories (
    id bigint NOT NULL,
    creative_job_id bigint NOT NULL,
    workflow_stage_id bigint NOT NULL,
    entered_by bigint,
    entered_at timestamp(0) without time zone,
    left_at timestamp(0) without time zone,
    duration_hours numeric(8,2) DEFAULT '0'::numeric NOT NULL,
    notes text,
    is_current boolean DEFAULT false NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.job_stage_histories OWNER TO mziyad;

--
-- Name: job_stage_histories_id_seq; Type: SEQUENCE; Schema: public; Owner: mziyad
--

CREATE SEQUENCE public.job_stage_histories_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.job_stage_histories_id_seq OWNER TO mziyad;

--
-- Name: job_stage_histories_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: mziyad
--

ALTER SEQUENCE public.job_stage_histories_id_seq OWNED BY public.job_stage_histories.id;


--
-- Name: job_statuses; Type: TABLE; Schema: public; Owner: mziyad
--

CREATE TABLE public.job_statuses (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    code character varying(255) NOT NULL,
    sort_order integer DEFAULT 0 NOT NULL,
    is_active boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.job_statuses OWNER TO mziyad;

--
-- Name: job_statuses_id_seq; Type: SEQUENCE; Schema: public; Owner: mziyad
--

CREATE SEQUENCE public.job_statuses_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.job_statuses_id_seq OWNER TO mziyad;

--
-- Name: job_statuses_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: mziyad
--

ALTER SEQUENCE public.job_statuses_id_seq OWNED BY public.job_statuses.id;


--
-- Name: jobs; Type: TABLE; Schema: public; Owner: mziyad
--

CREATE TABLE public.jobs (
    id bigint NOT NULL,
    queue character varying(255) NOT NULL,
    payload text NOT NULL,
    attempts smallint NOT NULL,
    reserved_at integer,
    available_at integer NOT NULL,
    created_at integer NOT NULL
);


ALTER TABLE public.jobs OWNER TO mziyad;

--
-- Name: jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: mziyad
--

CREATE SEQUENCE public.jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.jobs_id_seq OWNER TO mziyad;

--
-- Name: jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: mziyad
--

ALTER SEQUENCE public.jobs_id_seq OWNED BY public.jobs.id;


--
-- Name: marketing_campaign_recipients; Type: TABLE; Schema: public; Owner: mziyad
--

CREATE TABLE public.marketing_campaign_recipients (
    id bigint NOT NULL,
    campaign_id bigint NOT NULL,
    company_id bigint,
    contact_id bigint,
    status character varying(255) DEFAULT 'pending'::character varying NOT NULL,
    sent_at timestamp(0) without time zone,
    responded_at timestamp(0) without time zone,
    meta json,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.marketing_campaign_recipients OWNER TO mziyad;

--
-- Name: marketing_campaign_recipients_id_seq; Type: SEQUENCE; Schema: public; Owner: mziyad
--

CREATE SEQUENCE public.marketing_campaign_recipients_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.marketing_campaign_recipients_id_seq OWNER TO mziyad;

--
-- Name: marketing_campaign_recipients_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: mziyad
--

ALTER SEQUENCE public.marketing_campaign_recipients_id_seq OWNED BY public.marketing_campaign_recipients.id;


--
-- Name: marketing_campaigns; Type: TABLE; Schema: public; Owner: mziyad
--

CREATE TABLE public.marketing_campaigns (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    channel character varying(255) DEFAULT 'email'::character varying NOT NULL,
    status character varying(255) DEFAULT 'draft'::character varying NOT NULL,
    message_template text,
    created_by bigint,
    scheduled_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.marketing_campaigns OWNER TO mziyad;

--
-- Name: marketing_campaigns_id_seq; Type: SEQUENCE; Schema: public; Owner: mziyad
--

CREATE SEQUENCE public.marketing_campaigns_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.marketing_campaigns_id_seq OWNER TO mziyad;

--
-- Name: marketing_campaigns_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: mziyad
--

ALTER SEQUENCE public.marketing_campaigns_id_seq OWNED BY public.marketing_campaigns.id;


--
-- Name: migrations; Type: TABLE; Schema: public; Owner: mziyad
--

CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);


ALTER TABLE public.migrations OWNER TO mziyad;

--
-- Name: migrations_id_seq; Type: SEQUENCE; Schema: public; Owner: mziyad
--

CREATE SEQUENCE public.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.migrations_id_seq OWNER TO mziyad;

--
-- Name: migrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: mziyad
--

ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;


--
-- Name: password_reset_tokens; Type: TABLE; Schema: public; Owner: mziyad
--

CREATE TABLE public.password_reset_tokens (
    email character varying(255) NOT NULL,
    token character varying(255) NOT NULL,
    created_at timestamp(0) without time zone
);


ALTER TABLE public.password_reset_tokens OWNER TO mziyad;

--
-- Name: projects; Type: TABLE; Schema: public; Owner: mziyad
--

CREATE TABLE public.projects (
    id bigint NOT NULL,
    project_code character varying(255) NOT NULL,
    client_id bigint NOT NULL,
    name character varying(255) NOT NULL,
    description text,
    start_date date,
    end_date date,
    project_manager_id bigint,
    is_active boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.projects OWNER TO mziyad;

--
-- Name: projects_id_seq; Type: SEQUENCE; Schema: public; Owner: mziyad
--

CREATE SEQUENCE public.projects_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.projects_id_seq OWNER TO mziyad;

--
-- Name: projects_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: mziyad
--

ALTER SEQUENCE public.projects_id_seq OWNED BY public.projects.id;


--
-- Name: revisions; Type: TABLE; Schema: public; Owner: mziyad
--

CREATE TABLE public.revisions (
    id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.revisions OWNER TO mziyad;

--
-- Name: revisions_id_seq; Type: SEQUENCE; Schema: public; Owner: mziyad
--

CREATE SEQUENCE public.revisions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.revisions_id_seq OWNER TO mziyad;

--
-- Name: revisions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: mziyad
--

ALTER SEQUENCE public.revisions_id_seq OWNED BY public.revisions.id;


--
-- Name: roles; Type: TABLE; Schema: public; Owner: mziyad
--

CREATE TABLE public.roles (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    code character varying(255) NOT NULL,
    description text,
    is_active boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.roles OWNER TO mziyad;

--
-- Name: roles_id_seq; Type: SEQUENCE; Schema: public; Owner: mziyad
--

CREATE SEQUENCE public.roles_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.roles_id_seq OWNER TO mziyad;

--
-- Name: roles_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: mziyad
--

ALTER SEQUENCE public.roles_id_seq OWNED BY public.roles.id;


--
-- Name: sessions; Type: TABLE; Schema: public; Owner: mziyad
--

CREATE TABLE public.sessions (
    id character varying(255) NOT NULL,
    user_id bigint,
    ip_address character varying(45),
    user_agent text,
    payload text NOT NULL,
    last_activity integer NOT NULL
);


ALTER TABLE public.sessions OWNER TO mziyad;

--
-- Name: settings; Type: TABLE; Schema: public; Owner: mziyad
--

CREATE TABLE public.settings (
    id bigint NOT NULL,
    key character varying(255) NOT NULL,
    value text,
    "group" character varying(255) DEFAULT 'general'::character varying NOT NULL,
    type character varying(255) DEFAULT 'string'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.settings OWNER TO mziyad;

--
-- Name: settings_id_seq; Type: SEQUENCE; Schema: public; Owner: mziyad
--

CREATE SEQUENCE public.settings_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.settings_id_seq OWNER TO mziyad;

--
-- Name: settings_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: mziyad
--

ALTER SEQUENCE public.settings_id_seq OWNED BY public.settings.id;


--
-- Name: support_messages; Type: TABLE; Schema: public; Owner: mziyad
--

CREATE TABLE public.support_messages (
    id bigint NOT NULL,
    ticket_id bigint NOT NULL,
    user_id bigint,
    sender_type character varying(255) DEFAULT 'customer'::character varying NOT NULL,
    message text NOT NULL,
    meta json,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.support_messages OWNER TO mziyad;

--
-- Name: support_messages_id_seq; Type: SEQUENCE; Schema: public; Owner: mziyad
--

CREATE SEQUENCE public.support_messages_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.support_messages_id_seq OWNER TO mziyad;

--
-- Name: support_messages_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: mziyad
--

ALTER SEQUENCE public.support_messages_id_seq OWNED BY public.support_messages.id;


--
-- Name: support_tickets; Type: TABLE; Schema: public; Owner: mziyad
--

CREATE TABLE public.support_tickets (
    id bigint NOT NULL,
    ticket_number character varying(255) NOT NULL,
    client_id bigint,
    company_id bigint,
    assigned_to bigint,
    subject character varying(255) NOT NULL,
    status character varying(255) DEFAULT 'open'::character varying NOT NULL,
    priority character varying(255) DEFAULT 'normal'::character varying NOT NULL,
    channel character varying(255) DEFAULT 'website'::character varying NOT NULL,
    ai_summary text,
    closed_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.support_tickets OWNER TO mziyad;

--
-- Name: support_tickets_id_seq; Type: SEQUENCE; Schema: public; Owner: mziyad
--

CREATE SEQUENCE public.support_tickets_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.support_tickets_id_seq OWNER TO mziyad;

--
-- Name: support_tickets_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: mziyad
--

ALTER SEQUENCE public.support_tickets_id_seq OWNED BY public.support_tickets.id;


--
-- Name: teams; Type: TABLE; Schema: public; Owner: mziyad
--

CREATE TABLE public.teams (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    code character varying(255) NOT NULL,
    description text,
    is_active boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.teams OWNER TO mziyad;

--
-- Name: teams_id_seq; Type: SEQUENCE; Schema: public; Owner: mziyad
--

CREATE SEQUENCE public.teams_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.teams_id_seq OWNER TO mziyad;

--
-- Name: teams_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: mziyad
--

ALTER SEQUENCE public.teams_id_seq OWNED BY public.teams.id;


--
-- Name: user_outlook_accounts; Type: TABLE; Schema: public; Owner: mziyad
--

CREATE TABLE public.user_outlook_accounts (
    id bigint NOT NULL,
    user_id bigint NOT NULL,
    microsoft_user_id character varying(255),
    email character varying(255),
    display_name character varying(255),
    access_token text,
    refresh_token text,
    scopes json,
    token_expires_at timestamp(0) without time zone,
    is_connected boolean DEFAULT false NOT NULL,
    connected_at timestamp(0) without time zone,
    disconnected_at timestamp(0) without time zone,
    last_synced_at timestamp(0) without time zone,
    last_error text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.user_outlook_accounts OWNER TO mziyad;

--
-- Name: user_outlook_accounts_id_seq; Type: SEQUENCE; Schema: public; Owner: mziyad
--

CREATE SEQUENCE public.user_outlook_accounts_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.user_outlook_accounts_id_seq OWNER TO mziyad;

--
-- Name: user_outlook_accounts_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: mziyad
--

ALTER SEQUENCE public.user_outlook_accounts_id_seq OWNED BY public.user_outlook_accounts.id;


--
-- Name: users; Type: TABLE; Schema: public; Owner: mziyad
--

CREATE TABLE public.users (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    email_verified_at timestamp(0) without time zone,
    password character varying(255) NOT NULL,
    remember_token character varying(100),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    employee_no character varying(255),
    branch_id bigint,
    team_id bigint,
    role_id bigint,
    job_title character varying(255),
    mobile character varying(255),
    capacity_hours integer DEFAULT 8 NOT NULL,
    is_active boolean DEFAULT true NOT NULL
);


ALTER TABLE public.users OWNER TO mziyad;

--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: mziyad
--

CREATE SEQUENCE public.users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.users_id_seq OWNER TO mziyad;

--
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: mziyad
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- Name: workflow_stages; Type: TABLE; Schema: public; Owner: mziyad
--

CREATE TABLE public.workflow_stages (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    code character varying(255) NOT NULL,
    description text,
    sort_order integer DEFAULT 1 NOT NULL,
    color character varying(20) DEFAULT '#64748B'::character varying NOT NULL,
    icon character varying(255),
    is_start boolean DEFAULT false NOT NULL,
    is_end boolean DEFAULT false NOT NULL,
    requires_approval boolean DEFAULT false NOT NULL,
    allow_file_upload boolean DEFAULT true NOT NULL,
    allow_comments boolean DEFAULT true NOT NULL,
    is_active boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.workflow_stages OWNER TO mziyad;

--
-- Name: workflow_stages_id_seq; Type: SEQUENCE; Schema: public; Owner: mziyad
--

CREATE SEQUENCE public.workflow_stages_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.workflow_stages_id_seq OWNER TO mziyad;

--
-- Name: workflow_stages_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: mziyad
--

ALTER SEQUENCE public.workflow_stages_id_seq OWNED BY public.workflow_stages.id;


--
-- Name: workflow_transitions; Type: TABLE; Schema: public; Owner: mziyad
--

CREATE TABLE public.workflow_transitions (
    id bigint NOT NULL,
    from_stage_id bigint NOT NULL,
    to_stage_id bigint NOT NULL,
    name character varying(255),
    code character varying(255) NOT NULL,
    requires_permission boolean DEFAULT false NOT NULL,
    required_permission character varying(255),
    requires_comment boolean DEFAULT false NOT NULL,
    requires_file boolean DEFAULT false NOT NULL,
    is_active boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.workflow_transitions OWNER TO mziyad;

--
-- Name: workflow_transitions_id_seq; Type: SEQUENCE; Schema: public; Owner: mziyad
--

CREATE SEQUENCE public.workflow_transitions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.workflow_transitions_id_seq OWNER TO mziyad;

--
-- Name: workflow_transitions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: mziyad
--

ALTER SEQUENCE public.workflow_transitions_id_seq OWNED BY public.workflow_transitions.id;


--
-- Name: ai_approval_suggestions id; Type: DEFAULT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.ai_approval_suggestions ALTER COLUMN id SET DEFAULT nextval('public.ai_approval_suggestions_id_seq'::regclass);


--
-- Name: ai_interactions id; Type: DEFAULT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.ai_interactions ALTER COLUMN id SET DEFAULT nextval('public.ai_interactions_id_seq'::regclass);


--
-- Name: assets id; Type: DEFAULT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.assets ALTER COLUMN id SET DEFAULT nextval('public.assets_id_seq'::regclass);


--
-- Name: branches id; Type: DEFAULT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.branches ALTER COLUMN id SET DEFAULT nextval('public.branches_id_seq'::regclass);


--
-- Name: client_project_requests id; Type: DEFAULT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.client_project_requests ALTER COLUMN id SET DEFAULT nextval('public.client_project_requests_id_seq'::regclass);


--
-- Name: clients id; Type: DEFAULT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.clients ALTER COLUMN id SET DEFAULT nextval('public.clients_id_seq'::regclass);


--
-- Name: cms_pages id; Type: DEFAULT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.cms_pages ALTER COLUMN id SET DEFAULT nextval('public.cms_pages_id_seq'::regclass);


--
-- Name: creative_jobs id; Type: DEFAULT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.creative_jobs ALTER COLUMN id SET DEFAULT nextval('public.creative_jobs_id_seq'::regclass);


--
-- Name: crm_activities id; Type: DEFAULT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.crm_activities ALTER COLUMN id SET DEFAULT nextval('public.crm_activities_id_seq'::regclass);


--
-- Name: crm_companies id; Type: DEFAULT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.crm_companies ALTER COLUMN id SET DEFAULT nextval('public.crm_companies_id_seq'::regclass);


--
-- Name: crm_contacts id; Type: DEFAULT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.crm_contacts ALTER COLUMN id SET DEFAULT nextval('public.crm_contacts_id_seq'::regclass);


--
-- Name: crm_tasks id; Type: DEFAULT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.crm_tasks ALTER COLUMN id SET DEFAULT nextval('public.crm_tasks_id_seq'::regclass);


--
-- Name: email_intake_attachments id; Type: DEFAULT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.email_intake_attachments ALTER COLUMN id SET DEFAULT nextval('public.email_intake_attachments_id_seq'::regclass);


--
-- Name: email_intake_traffic_members id; Type: DEFAULT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.email_intake_traffic_members ALTER COLUMN id SET DEFAULT nextval('public.email_intake_traffic_members_id_seq'::regclass);


--
-- Name: email_intake_validations id; Type: DEFAULT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.email_intake_validations ALTER COLUMN id SET DEFAULT nextval('public.email_intake_validations_id_seq'::regclass);


--
-- Name: email_intakes id; Type: DEFAULT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.email_intakes ALTER COLUMN id SET DEFAULT nextval('public.email_intakes_id_seq'::regclass);


--
-- Name: failed_jobs id; Type: DEFAULT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.failed_jobs ALTER COLUMN id SET DEFAULT nextval('public.failed_jobs_id_seq'::regclass);


--
-- Name: job_activities id; Type: DEFAULT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.job_activities ALTER COLUMN id SET DEFAULT nextval('public.job_activities_id_seq'::regclass);


--
-- Name: job_assignments id; Type: DEFAULT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.job_assignments ALTER COLUMN id SET DEFAULT nextval('public.job_assignments_id_seq'::regclass);


--
-- Name: job_categories id; Type: DEFAULT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.job_categories ALTER COLUMN id SET DEFAULT nextval('public.job_categories_id_seq'::regclass);


--
-- Name: job_stage_histories id; Type: DEFAULT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.job_stage_histories ALTER COLUMN id SET DEFAULT nextval('public.job_stage_histories_id_seq'::regclass);


--
-- Name: job_statuses id; Type: DEFAULT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.job_statuses ALTER COLUMN id SET DEFAULT nextval('public.job_statuses_id_seq'::regclass);


--
-- Name: jobs id; Type: DEFAULT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.jobs ALTER COLUMN id SET DEFAULT nextval('public.jobs_id_seq'::regclass);


--
-- Name: marketing_campaign_recipients id; Type: DEFAULT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.marketing_campaign_recipients ALTER COLUMN id SET DEFAULT nextval('public.marketing_campaign_recipients_id_seq'::regclass);


--
-- Name: marketing_campaigns id; Type: DEFAULT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.marketing_campaigns ALTER COLUMN id SET DEFAULT nextval('public.marketing_campaigns_id_seq'::regclass);


--
-- Name: migrations id; Type: DEFAULT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);


--
-- Name: projects id; Type: DEFAULT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.projects ALTER COLUMN id SET DEFAULT nextval('public.projects_id_seq'::regclass);


--
-- Name: revisions id; Type: DEFAULT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.revisions ALTER COLUMN id SET DEFAULT nextval('public.revisions_id_seq'::regclass);


--
-- Name: roles id; Type: DEFAULT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.roles ALTER COLUMN id SET DEFAULT nextval('public.roles_id_seq'::regclass);


--
-- Name: settings id; Type: DEFAULT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.settings ALTER COLUMN id SET DEFAULT nextval('public.settings_id_seq'::regclass);


--
-- Name: support_messages id; Type: DEFAULT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.support_messages ALTER COLUMN id SET DEFAULT nextval('public.support_messages_id_seq'::regclass);


--
-- Name: support_tickets id; Type: DEFAULT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.support_tickets ALTER COLUMN id SET DEFAULT nextval('public.support_tickets_id_seq'::regclass);


--
-- Name: teams id; Type: DEFAULT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.teams ALTER COLUMN id SET DEFAULT nextval('public.teams_id_seq'::regclass);


--
-- Name: user_outlook_accounts id; Type: DEFAULT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.user_outlook_accounts ALTER COLUMN id SET DEFAULT nextval('public.user_outlook_accounts_id_seq'::regclass);


--
-- Name: users id; Type: DEFAULT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- Name: workflow_stages id; Type: DEFAULT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.workflow_stages ALTER COLUMN id SET DEFAULT nextval('public.workflow_stages_id_seq'::regclass);


--
-- Name: workflow_transitions id; Type: DEFAULT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.workflow_transitions ALTER COLUMN id SET DEFAULT nextval('public.workflow_transitions_id_seq'::regclass);


--
-- Data for Name: ai_approval_suggestions; Type: TABLE DATA; Schema: public; Owner: mziyad
--

COPY public.ai_approval_suggestions (id, type, title, summary, subject_type, subject_id, payload, status, created_by, approved_by, approved_at, rejected_at, decision_notes, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: ai_interactions; Type: TABLE DATA; Schema: public; Owner: mziyad
--

COPY public.ai_interactions (id, provider, agent, subject_type, subject_id, user_id, prompt, response, meta, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: assets; Type: TABLE DATA; Schema: public; Owner: mziyad
--

COPY public.assets (id, creative_job_id, uploaded_by, file_name, original_name, file_type, mime_type, file_size, storage_type, storage_path, version, asset_stage, is_final, notes, created_at, updated_at) FROM stdin;
1	2	1	UmoOH62ahcflF7yV4TINKoCkdUnrF79uqozlNIG7.jpg	POST-D_6.jpg	jpg	image/jpeg	969494	local	creative-jobs/JOB-2026-00002/briefs/UmoOH62ahcflF7yV4TINKoCkdUnrF79uqozlNIG7.jpg	1	BRIEF	f	\N	2026-07-01 13:21:43	2026-07-01 13:21:43
2	2	1	pJjb1Brmk4v6x7AhaUcuZ5iVI1Y5L5BQGHassHDl.jpg	Teashop-Strip-50x5cm-02.jpg	jpg	image/jpeg	118598	local	creative-jobs/JOB-2026-00002/briefs/pJjb1Brmk4v6x7AhaUcuZ5iVI1Y5L5BQGHassHDl.jpg	1	BRIEF	f	\N	2026-07-01 13:22:30	2026-07-01 13:22:30
\.


--
-- Data for Name: branches; Type: TABLE DATA; Schema: public; Owner: mziyad
--

COPY public.branches (id, name, code, country, city, is_active, created_at, updated_at) FROM stdin;
1	Jeddah HQ	JED	Saudi Arabia	Jeddah	t	\N	\N
2	Riyadh	RUH	Saudi Arabia	Riyadh	t	\N	\N
3	Cairo	CAI	Egypt	Cairo	t	\N	\N
4	Beirut	BEY	Lebanon	Beirut	t	\N	\N
\.


--
-- Data for Name: cache; Type: TABLE DATA; Schema: public; Owner: mziyad
--

COPY public.cache (key, value, expiration) FROM stdin;
\.


--
-- Data for Name: cache_locks; Type: TABLE DATA; Schema: public; Owner: mziyad
--

COPY public.cache_locks (key, owner, expiration) FROM stdin;
\.


--
-- Data for Name: client_project_requests; Type: TABLE DATA; Schema: public; Owner: mziyad
--

COPY public.client_project_requests (id, request_number, client_id, project_id, type, title, brief, target_country, desired_launch_date, budget_range, priority, status, deliverables, attachments, external_links, admin_notes, reviewed_at, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: clients; Type: TABLE DATA; Schema: public; Owner: mziyad
--

COPY public.clients (id, client_code, name, branch_id, account_manager_id, industry, email, phone, is_active, created_at, updated_at, password, remember_token, portal_enabled, last_login_at, company_name, contact_person, country, city, website, avatar_path, company_profile) FROM stdin;
1	DEMO	democ@pgintrgrated.com	1	2	Advertising	democ@pgintrgrated.com	0000000000	t	2026-06-30 14:13:26	2026-07-05 13:20:18	$2y$12$yFP5SAm6G78NYSGNLqhoUeS9GUWMdon0Sw30cnbGfgE8upJwljZzK	\N	t	2026-07-05 13:20:18	\N	\N	\N	\N	\N	\N	\N
2	000198	demo	1	1	Food	demo-archived-2@pgintegrated.local	05012345678	t	2026-07-05 13:23:02	2026-07-05 13:27:47	$2y$12$2G/Aa2R9O8xO2O3P812ik.BlGCET2ngfLOz6MY7SqpdC6ykXj7aaC	\N	f	\N	\N	\N	\N	\N	\N	\N	\N
3	PG-DEMO	PG Integrated Demo Client	1	2	Retail & FMCG	demo@pgintegrated.com	+966500000000	t	2026-07-05 13:26:43	2026-07-05 13:59:34	$2y$12$LI6TQsu7drav6m8CjKgGZe4xxNo1Zpd0VeViBrEso8j8Yai2jT..e	\N	t	2026-07-05 13:59:34	Demo Retail Company	Demo Client Manager	Saudi Arabia	Jeddah	https://pgintegrated.com	\N	Demo account for reviewing the client journey, projects, jobs, requests, attachments, and campaign calendar.
\.


--
-- Data for Name: cms_pages; Type: TABLE DATA; Schema: public; Owner: mziyad
--

COPY public.cms_pages (id, key, title, slug, sections, seo, is_published, updated_by, created_at, updated_at) FROM stdin;
1	home	Home	home	{"type":"home","hero":{"eyebrow":"PG Integrated \\u00b7 Jeddah \\u00b7 Riyadh","title":"Integrated ideas for brands that need to move.","body":"A multi-disciplinary agency connecting strategy, creative, digital, production, and delivery operations in one accountable rhythm.","primary_label":"Start a conversation","primary_url":"mailto:mziyad@pgintegrated.com","secondary_label":"View our work","secondary_url":"\\/work","image":"\\/prd-assets\\/images\\/homepage-option18-banner.jpg"},"stats":[{"value":"41+","label":"Years of experience"},{"value":"150+","label":"Marketing specialists"},{"value":"360\\u00b0","label":"Integrated delivery"}],"intro":{"eyebrow":"About PG","title":"A Saudi-rooted agency built for end-to-end marketing.","body":"PG Integrated is a multi-disciplinary agency offering end-to-end marketing solutions, from strategy and brand narrative to design, digital, production, and activation."},"services":{"title":"Services","body":"Strategy, positioning, creative concepts, web and digital experiences, graphic design, production, and brand activation.","items":[{"title":"Strategy & Positioning","body":"Market studies, brand architecture, narratives, and campaign direction."},{"title":"Creative & Design","body":"Concepts, identities, graphic design, motion, and content systems."},{"title":"Digital & Web","body":"Interactive websites, digital campaigns, social assets, and platform thinking."}]},"portfolio":{"title":"Selected Work","items":[{"title":"Campaign Key Visual","image":"\\/prd-assets\\/PORTFOLIO\\/Jeddah-Riyadh-KV-04-1200x846.jpeg"},{"title":"Retail Activation","image":"\\/prd-assets\\/PORTFOLIO\\/11512-Panda-36th-Anniversary-In_Mall-Mupi-70x120cm.jpeg"},{"title":"FMCG Campaign","image":"\\/prd-assets\\/PORTFOLIO\\/01-AM-Lemon-Mint-Campaign-A1-Poster-2-724x1024.jpeg"}]},"cta":{"title":"Have a brief in mind?","body":"Send the brief to PG Integrated and the team can shape the right next step.","label":"Contact PG Integrated","url":"mailto:mziyad@pgintegrated.com"}}	{"title":"PG Integrated | Creative, Digital & Production","description":"Integrated strategy, creative, digital, production, and client operations for ambitious brands."}	t	\N	2026-07-05 08:49:18	2026-07-05 12:36:16
2	about	About	about	{"type":"content","hero":{"eyebrow":"About","title":"One agency. One integrated operating model.","body":"PG Integrated connects strategy, creative, digital, production, and delivery so brands can move from idea to execution with clarity.","image":"\\/prd-assets\\/images\\/business-about-bg.jpg"},"items":[{"title":"End-to-end thinking","body":"From business challenge to strategy, creative routes, production, and market delivery."},{"title":"Local insight","body":"A Saudi-market perspective with a long record of regional brand work."},{"title":"Operational visibility","body":"The platform connects briefs, jobs, teams, clients, support, CRM, and reports."}]}	{"title":"About PG Integrated","description":"Learn about PG Integrated and its end-to-end agency model."}	t	\N	2026-07-05 12:36:16	2026-07-05 12:36:16
3	services	Services	services	{"type":"services","hero":{"eyebrow":"Services","title":"Strategy, creative, digital, and production under one roof.","body":"Services are structured around clear outcomes: sharper brands, stronger campaigns, cleaner delivery, and better visibility for clients.","image":"\\/prd-assets\\/O2.jpeg"},"items":[{"title":"Strategy & Positioning","body":"Global studies, market mapping, brand positioning, campaign planning, and storytelling."},{"title":"Concept & Creative","body":"Creative concepts, key visuals, art direction, copywriting, content systems, and campaign toolkits."},{"title":"Digital Branding","body":"Web interfaces, digital content, interactive experiences, social campaigns, and performance assets."},{"title":"Graphic Design","body":"Identity applications, packaging, presentation systems, print, OOH, and production-ready artwork."},{"title":"Production","body":"Photography, video, post-production, motion, and scalable adaptations."},{"title":"Client Operations","body":"Brief intake, project tracking, support tickets, approvals, reporting, and archive management."}]}	{"title":"PG Integrated Services","description":"Strategy, creative, digital, design, production, and client operations services."}	t	\N	2026-07-05 12:36:16	2026-07-05 12:36:16
4	work	Work	work	{"type":"portfolio","hero":{"eyebrow":"Work","title":"Selected campaigns and brand work.","body":"A view of campaign visuals, activations, and creative systems drawn from the PRD portfolio assets."},"items":[{"title":"Jeddah Riyadh Campaign","image":"\\/prd-assets\\/PORTFOLIO\\/Jeddah-Riyadh-KV-04-1200x846.jpeg"},{"title":"Juffali Saudization","image":"\\/prd-assets\\/PORTFOLIO\\/Juffali-Saudization-1.jpeg"},{"title":"Panda Anniversary","image":"\\/prd-assets\\/PORTFOLIO\\/11512-Panda-36th-Anniversary-In_Mall-Mupi-70x120cm.jpeg"},{"title":"Almarai Lemon Mint","image":"\\/prd-assets\\/PORTFOLIO\\/01-AM-Lemon-Mint-Campaign-A1-Poster-2-724x1024.jpeg"},{"title":"Saptco Go Bus","image":"\\/prd-assets\\/PORTFOLIO\\/Saptco-go-bus-A5-FINAL-FINAL-01.jpg"},{"title":"Velor Campaign","image":"\\/prd-assets\\/PORTFOLIO\\/Velor-KV-A3-Temp-WHITE-01-01.jpeg"}]}	{"title":"PG Integrated Work","description":"Selected campaign, activation, and creative work by PG Integrated."}	t	\N	2026-07-05 12:36:16	2026-07-05 12:36:16
5	team	Team	team	{"type":"team","hero":{"eyebrow":"Team","title":"Leadership and specialist teams.","body":"The public team interface uses the PRD team assets and can be edited from the CMS page data."},"items":[{"name":"Ahmad Kammoun","role":"Managing Director","image":"\\/prd-assets\\/Team\\/Ahmed Kammoun.png"},{"name":"Imad Beyhum","role":"Client Leadership","image":"\\/prd-assets\\/Team\\/Imad Beyhum.jpg"},{"name":"Riad Chehab","role":"Creative Leadership","image":"\\/prd-assets\\/Team\\/Riad Chehab.jpg"},{"name":"Hamoud Al Harbi","role":"Operations","image":"\\/prd-assets\\/Team\\/Hamoud Al Harbi.jpg"},{"name":"Amr Sallam","role":"Digital & Accounts","image":"\\/prd-assets\\/Team\\/Amr Sallam.jpeg"}]}	{"title":"PG Integrated Team","description":"Meet the leadership and specialist teams at PG Integrated."}	t	\N	2026-07-05 12:36:16	2026-07-05 12:36:16
6	clients	Clients	clients	{"type":"clients","hero":{"eyebrow":"Clients","title":"Trusted by regional and international brands.","body":"Client logos and references from the PRD client library."},"items":[{"name":"Unilever","image":"\\/prd-assets\\/Client\\/Unilever.jpeg"},{"name":"Vision 2030","image":"\\/prd-assets\\/Client\\/Vision-2030-logo-250x202.jpeg"},{"name":"Mercedes-Benz","image":"\\/prd-assets\\/Client\\/Mercedes-Benz-Logo-300x202.jpeg"},{"name":"AMG","image":"\\/prd-assets\\/Client\\/AMG-logo-300x202.jpeg"},{"name":"Alrajhi Takaful","image":"\\/prd-assets\\/Client\\/Alrajhi-Takaful-logo-250x202.jpeg"},{"name":"BAE Systems","image":"\\/prd-assets\\/Client\\/BAE-systems-Logo-320x202.jpeg"},{"name":"Bank AlJazira","image":"\\/prd-assets\\/Client\\/BANK-ALJAZIRA.jpeg"},{"name":"Binzagr","image":"\\/prd-assets\\/Client\\/Binzagr-logo-250x202.jpeg"},{"name":"Comfort","image":"\\/prd-assets\\/Client\\/Comfort.jpeg"},{"name":"Diet Center","image":"\\/prd-assets\\/Client\\/Diet-Center-logo-300x202.jpeg"},{"name":"Faten","image":"\\/prd-assets\\/Client\\/Faten-logo-250x202.jpeg"},{"name":"HRDF","image":"\\/prd-assets\\/Client\\/HRDF-logo-250x202.jpeg"}]}	{"title":"PG Integrated Clients","description":"A selection of clients and brands served by PG Integrated."}	t	\N	2026-07-05 12:36:16	2026-07-05 12:36:16
7	contact	Contact	contact	{"type":"contact","hero":{"eyebrow":"Contact","title":"Let\\u2019s build the next brief.","body":"Reach PG Integrated for new briefs, partnerships, careers, and platform access.","image":"\\/prd-assets\\/O5.jpeg"},"contacts":[{"label":"Email","value":"mziyad@pgintegrated.com","url":"mailto:Salse@pgintegrated.com"},{"label":"Info","value":"info@pgintegrated.com","url":"mailto:info@pgintegrated.com"},{"label":"Phone","value":"+966 12 663 5959","url":"tel:+966126635959"},{"label":"Fax","value":"+966 12 665 6423"}],"locations":[{"city":"Jeddah","address":"PG Integrated main office"},{"city":"Riyadh","address":"PG Integrated regional operations"}]}	{"title":"Contact PG Integrated","description":"Contact PG Integrated for briefs, partnerships, careers, and access."}	t	2	2026-07-05 12:36:16	2026-07-05 13:10:41
\.


--
-- Data for Name: creative_jobs; Type: TABLE DATA; Schema: public; Owner: mziyad
--

COPY public.creative_jobs (id, job_number, client_id, project_id, job_category_id, job_status_id, traffic_manager_id, project_manager_id, title, brief, priority, received_at, first_draft_due_at, final_due_at, first_draft_sent_at, final_delivered_at, estimated_hours, actual_hours, revision_count, reopened_count, completion_percentage, internal_notes, client_notes, nas_folder_path, dropbox_folder_path, final_delivery_path, is_archived, archived_at, created_by, created_at, updated_at, current_workflow_stage_id) FROM stdin;
1	JOB-2026-00001	1	1	\N	\N	\N	\N	test	test	HIGH	2026-07-01 08:05:10	2026-07-01 11:04:00	2026-07-01 00:33:00	\N	\N	3.00	0.00	0	0	0	\N	\N	\N	\N	\N	f	\N	1	2026-07-01 08:05:10	2026-07-01 08:05:10	2
2	JOB-2026-00002	1	1	\N	\N	\N	\N	Memories July Content - 18010	Memories July Content	LOW	2026-07-01 09:13:38	2026-07-02 12:12:00	2026-07-02 12:12:00	\N	\N	16.00	0.00	0	0	0	\N	\N	\N	\N	\N	f	\N	1	2026-07-01 09:13:38	2026-07-01 09:13:38	2
3	PG-DEMO-JOB-001	3	2	\N	\N	2	2	Campaign Strategy & Brand Narrative	Define the communication strategy, brand story, and campaign direction.	HIGH	2026-06-28 13:56:25	2026-07-03 13:56:25	2026-07-09 13:56:25	\N	\N	18.00	7.00	1	0	80	\N	Strategy route shared for client review.	\N	https://www.dropbox.com/	https://wetransfer.com/	f	\N	2	2026-07-05 13:26:43	2026-07-05 13:56:25	4
4	PG-DEMO-JOB-002	3	2	\N	\N	2	2	Key Visual Design	Develop main campaign key visual and visual system.	URGENT	2026-06-28 13:56:25	2026-07-06 13:56:25	2026-07-13 13:56:25	\N	\N	18.00	7.00	1	0	55	\N	Creative team preparing first visual options.	\N	https://www.dropbox.com/	\N	f	\N	2	2026-07-05 13:26:43	2026-07-05 13:56:25	3
5	PG-DEMO-JOB-003	3	3	\N	\N	2	2	July Social Media Content	Create monthly content calendar and design posts for social channels.	MEDIUM	2026-06-28 13:56:25	2026-07-08 13:56:25	2026-07-17 13:56:25	\N	\N	18.00	7.00	1	0	35	\N	Calendar structure is in progress.	\N	https://www.dropbox.com/	\N	f	\N	2	2026-07-05 13:26:43	2026-07-05 13:56:25	2
\.


--
-- Data for Name: crm_activities; Type: TABLE DATA; Schema: public; Owner: mziyad
--

COPY public.crm_activities (id, company_id, contact_id, user_id, type, channel, summary, body, activity_at, meta, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: crm_companies; Type: TABLE DATA; Schema: public; Owner: mziyad
--

COPY public.crm_companies (id, name, industry, country, website, linkedin_url, source, status, lead_score, expected_value, owner_id, last_contacted_at, next_follow_up_at, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: crm_contacts; Type: TABLE DATA; Schema: public; Owner: mziyad
--

COPY public.crm_contacts (id, company_id, name, "position", email, phone, whatsapp, is_primary, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: crm_tasks; Type: TABLE DATA; Schema: public; Owner: mziyad
--

COPY public.crm_tasks (id, company_id, assigned_to, title, description, status, due_at, completed_at, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: email_intake_attachments; Type: TABLE DATA; Schema: public; Owner: mziyad
--

COPY public.email_intake_attachments (id, email_intake_id, outlook_attachment_id, name, content_type, size, is_inline, is_brief, storage_disk, storage_path, sha256, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: email_intake_traffic_members; Type: TABLE DATA; Schema: public; Owner: mziyad
--

COPY public.email_intake_traffic_members (id, user_id, outlook_email, is_active, receives_notifications, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: email_intake_validations; Type: TABLE DATA; Schema: public; Owner: mziyad
--

COPY public.email_intake_validations (id, email_intake_id, rule, passed, message, context, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: email_intakes; Type: TABLE DATA; Schema: public; Owner: mziyad
--

COPY public.email_intakes (id, source, message_id, sender_email, sender_name, subject, body, received_at, has_attachments, status, creative_job_id, raw_payload, created_at, updated_at, conversation_id, internet_message_id, to_recipients, cc_recipients, extracted_job_number, validation_passed, validation_errors, rejection_reason, reviewed_by, reviewed_at, accepted_at, rejected_at) FROM stdin;
\.


--
-- Data for Name: failed_jobs; Type: TABLE DATA; Schema: public; Owner: mziyad
--

COPY public.failed_jobs (id, uuid, connection, queue, payload, exception, failed_at) FROM stdin;
\.


--
-- Data for Name: job_activities; Type: TABLE DATA; Schema: public; Owner: mziyad
--

COPY public.job_activities (id, creative_job_id, user_id, activity, activity_type, description, old_values, new_values, ip_address, device, activity_at, created_at, updated_at) FROM stdin;
1	2	1	JOB_ASSIGNED	USER	Job assigned successfully.	\N	\N	\N	\N	2026-07-01 10:18:20	2026-07-01 10:18:20	2026-07-01 10:18:20
2	2	1	ATTACHMENTS_UPLOADED	USER	Brief attachments uploaded successfully.	\N	\N	\N	\N	2026-07-01 13:21:43	2026-07-01 13:21:43	2026-07-01 13:21:43
3	2	1	ATTACHMENTS_UPLOADED	USER	Brief attachments uploaded successfully.	\N	\N	\N	\N	2026-07-01 13:22:30	2026-07-01 13:22:30	2026-07-01 13:22:30
\.


--
-- Data for Name: job_assignments; Type: TABLE DATA; Schema: public; Owner: mziyad
--

COPY public.job_assignments (id, creative_job_id, team_id, user_id, assigned_by, assigned_at, estimated_hours, actual_hours, started_at, completed_at, status, notes, created_at, updated_at, supervisor_id) FROM stdin;
1	2	6	1	1	2026-07-01 10:15:21	12.00	0.00	\N	\N	ASSIGNED	PSD file in below link	2026-07-01 10:15:21	2026-07-01 10:15:21	1
2	1	7	1	1	2026-07-01 10:15:46	12.00	0.00	\N	\N	ASSIGNED	d	2026-07-01 10:15:46	2026-07-01 10:15:46	1
3	1	7	1	1	2026-07-01 10:16:24	12.00	0.00	\N	\N	ASSIGNED	d	2026-07-01 10:16:24	2026-07-01 10:16:24	1
4	2	7	1	1	2026-07-01 10:16:44	1.00	0.00	\N	\N	ASSIGNED	test	2026-07-01 10:16:44	2026-07-01 10:16:44	1
5	2	3	1	1	2026-07-01 10:18:20	12.00	0.00	\N	\N	ASSIGNED	test	2026-07-01 10:18:20	2026-07-01 10:18:20	1
\.


--
-- Data for Name: job_batches; Type: TABLE DATA; Schema: public; Owner: mziyad
--

COPY public.job_batches (id, name, total_jobs, pending_jobs, failed_jobs, failed_job_ids, options, cancelled_at, created_at, finished_at) FROM stdin;
\.


--
-- Data for Name: job_categories; Type: TABLE DATA; Schema: public; Owner: mziyad
--

COPY public.job_categories (id, name, code, description, is_active, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: job_stage_histories; Type: TABLE DATA; Schema: public; Owner: mziyad
--

COPY public.job_stage_histories (id, creative_job_id, workflow_stage_id, entered_by, entered_at, left_at, duration_hours, notes, is_current, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: job_statuses; Type: TABLE DATA; Schema: public; Owner: mziyad
--

COPY public.job_statuses (id, name, code, sort_order, is_active, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: jobs; Type: TABLE DATA; Schema: public; Owner: mziyad
--

COPY public.jobs (id, queue, payload, attempts, reserved_at, available_at, created_at) FROM stdin;
\.


--
-- Data for Name: marketing_campaign_recipients; Type: TABLE DATA; Schema: public; Owner: mziyad
--

COPY public.marketing_campaign_recipients (id, campaign_id, company_id, contact_id, status, sent_at, responded_at, meta, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: marketing_campaigns; Type: TABLE DATA; Schema: public; Owner: mziyad
--

COPY public.marketing_campaigns (id, name, channel, status, message_template, created_by, scheduled_at, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: migrations; Type: TABLE DATA; Schema: public; Owner: mziyad
--

COPY public.migrations (id, migration, batch) FROM stdin;
1	0001_01_01_000000_create_users_table	1
2	0001_01_01_000001_create_cache_table	1
3	0001_01_01_000002_create_jobs_table	1
4	2026_06_30_081720_create_branches_table	1
5	2026_06_30_083757_create_teams_table	2
6	2026_06_30_084133_create_roles_table	3
7	2026_06_30_084359_add_operations_fields_to_users_table	4
8	2026_06_30_102018_create_clients_table	5
9	2026_06_30_102548_create_projects_table	6
10	2026_06_30_103143_create_job_statuses_table	7
11	2026_06_30_103714_create_job_categories_table	8
12	2026_06_30_103714_create_job_statuses_table	8
13	2026_06_30_105643_create_jobs_table	8
14	2026_06_30_110512_create_job_assignments_table	9
15	2026_06_30_110637_create_assets_table	10
16	2026_06_30_110751_create_revisions_table	11
17	2026_06_30_124413_create_email_intakes_table	12
18	2026_06_30_131954_create_job_activities_table	13
19	2026_06_30_131950_create_workflow_stages_table	14
20	2026_06_30_131951_create_workflow_transitions_table	14
21	2026_06_30_131952_create_job_stage_histories_table	14
22	2026_06_30_131953_create_job_activities_table	15
23	2026_06_30_135148_add_current_workflow_stage_to_creative_jobs_table	15
24	2026_07_01_095220_add_supervisor_to_job_assignments_table	16
25	2026_07_02_000000_create_settings_table	17
26	2026_07_02_100000_enhance_email_intake_module	18
27	2026_07_02_110000_create_user_outlook_accounts_table	19
28	2026_07_04_000001_create_cms_pages_table	20
29	2026_07_04_000002_create_crm_tables	20
30	2026_07_04_000003_create_support_tickets_table	20
31	2026_07_04_000004_create_ai_and_marketing_tables	20
32	2026_07_05_000001_add_portal_auth_to_clients_table	21
33	2026_07_05_000002_enhance_client_portal_profile	22
34	2026_07_05_000003_create_client_project_requests_table	22
35	2026_07_05_000004_create_ai_approval_suggestions_table	23
\.


--
-- Data for Name: password_reset_tokens; Type: TABLE DATA; Schema: public; Owner: mziyad
--

COPY public.password_reset_tokens (email, token, created_at) FROM stdin;
\.


--
-- Data for Name: projects; Type: TABLE DATA; Schema: public; Owner: mziyad
--

COPY public.projects (id, project_code, client_id, name, description, start_date, end_date, project_manager_id, is_active, created_at, updated_at) FROM stdin;
1	DEMO-2026	1	Demo Campaign	Test project for PG Integrated	\N	\N	\N	t	2026-06-30 14:13:26	2026-06-30 14:13:26
2	PG-DEMO-001	3	Brand Launch Campaign	Client journey demo project covering strategy, key visual, adaptation, and delivery.	2026-06-20	2026-08-04	2	t	2026-07-05 13:26:43	2026-07-05 13:26:43
3	PG-DEMO-002	3	Monthly Digital Content	Ongoing monthly social and digital content package for client portal review.	2026-06-30	2026-09-05	2	t	2026-07-05 13:26:43	2026-07-05 13:26:43
\.


--
-- Data for Name: revisions; Type: TABLE DATA; Schema: public; Owner: mziyad
--

COPY public.revisions (id, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: roles; Type: TABLE DATA; Schema: public; Owner: mziyad
--

COPY public.roles (id, name, code, description, is_active, created_at, updated_at) FROM stdin;
1	Super Admin	SUPER_ADMIN	System Administrator	t	\N	\N
2	Operations Manager	OPERATIONS_MANAGER	Operations Manager	t	\N	\N
3	Traffic Manager	TRAFFIC_MANAGER	Traffic Manager	t	\N	\N
4	Account Manager	ACCOUNT_MANAGER	Account Manager	t	\N	\N
5	Designer	DESIGNER	Graphic Designer	t	\N	\N
6	Senior Designer	SENIOR_DESIGNER	Senior Designer	t	\N	\N
7	Content Writer	CONTENT_WRITER	Content Writer	t	\N	\N
8	Motion Designer	MOTION_DESIGNER	Motion Graphics Designer	t	\N	\N
9	QA Reviewer	QA_REVIEWER	Quality Assurance Reviewer	t	\N	\N
10	Archive Officer	ARCHIVE_OFFICER	Archive Officer	t	\N	\N
\.


--
-- Data for Name: sessions; Type: TABLE DATA; Schema: public; Owner: mziyad
--

COPY public.sessions (id, user_id, ip_address, user_agent, payload, last_activity) FROM stdin;
n1zzosRjt38YkASJFVX3Vb1KVEdrymc1XuAdvsoe	\N	127.0.0.1		eyJfdG9rZW4iOiJ6aGlONmpZdTB6ZW9GV01xVmZnVkt1aUpFOTVUdG0xeDJMUEprRDR3IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDEwXC93b3JrIiwicm91dGUiOiJ3ZWJzaXRlLndvcmsifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==	1783245633
NiM5iGoUj7ggIWNSZEiaF5UKRW7YXlGkF06243fm	\N	127.0.0.1		eyJfdG9rZW4iOiJYRThUOUJmSnBRalpCWUxZMVZHdUk5bEtTRXhSRmwzdjZZVlBkUmNZIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDEwXC9jbGllbnRcL2xvZ2luIiwicm91dGUiOiJjbGllbnQubG9naW4ifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==	1783249075
UjTRdlMS0eqzh8vPdJEnZgYtWwaOqqz5IvAJDcCs	\N	127.0.0.1		eyJfdG9rZW4iOiJoVXZ3dXlLdjNzbUxFaUV1bTZ3Q05Oa2JzaHIwVkk5elVnenNjMEJiIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDEwXC9sb2dpbiIsInJvdXRlIjoibG9naW4ifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==	1783249075
q76wmkgHArjFmIgWUrfNk4NtP0Sojb0D7j3OoTj6	\N	127.0.0.1		eyJfdG9rZW4iOiJSeXpUZ1VHRDB5MUZ5UWtoYkZSU0NNS3RGbUt0eGxNY3I1ZFl2c1pSIiwidXJsIjp7ImludGVuZGVkIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMTBcL2FkbWluXC9jbXMifSwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDEwXC9hZG1pblwvY21zIiwicm91dGUiOiJhZG1pbi5jbXMuaW5kZXgifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==	1783244918
pvVD8rKfnHOxMJyw7gwkbQurhy8yhaRCzY1U5At6	\N	127.0.0.1		eyJfdG9rZW4iOiJ2Nm9oU09zTkNDVWlRblVJaWhyRE02dGxmSnR0c256eXYzMEF3MUluIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDEwXC9sb2dpbiIsInJvdXRlIjoibG9naW4ifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==	1783244918
NIIP5OPMT31RpxUxnTrVOgKr6cPCVlSOk5eYFkEy	\N	127.0.0.1		eyJfdG9rZW4iOiJkTTNGb2Z0NnpkcXJSdXpWQVdHU2MwMHNHZ01KR0d2dGpkaVlWdnNFIiwidXJsIjp7ImludGVuZGVkIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMTBcL2FkbWluXC9jbXNcLzFcL2VkaXQifSwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDEwXC9hZG1pblwvY21zXC8xXC9lZGl0Iiwicm91dGUiOiJhZG1pbi5jbXMuZWRpdCJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19	1783244918
5kmJRcp3N9USwALVTDS2eweVLEG8iWWMPQwlABhJ	\N	127.0.0.1		eyJfdG9rZW4iOiJwOUdxNVY5b2MycnlJT2tSbEZaVmdKeU96cUVnWm4wdGkyQzZ4NGJtIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDEwXC9sb2dpbiIsInJvdXRlIjoibG9naW4ifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==	1783244918
hvmW7y2giHTB63RBcQwV8IYqkWAVtdIwxGpUMsB4	\N	127.0.0.1		eyJfdG9rZW4iOiJpV2tYYVNrYjQ3ZjJ3c1k5ZXR6Nzl5bW1xMEZFdndxYWpKcU5DS3FxIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDEwIiwicm91dGUiOiJ3ZWJzaXRlLmhvbWUifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==	1783244219
1cj8GxQ8AwTZeYQBq6TZWQKRPngd2b1kTsMmdONH	\N	127.0.0.1		eyJfdG9rZW4iOiJIY3FjV2phZVpXV1NGVGVmZHQ3TER0dXFhZHh4TmtnTlpJVFdMMzR0IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDEwXC9hYm91dCIsInJvdXRlIjoid2Vic2l0ZS5hYm91dCJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19	1783244219
SgikdveaZ60X8TmsHIdJDZOdnmsz6nKupgrQ1bus	\N	127.0.0.1		eyJfdG9rZW4iOiIxMVo5RHhxTTRHMzRBVHNtSHozaHgzQmZodVBzWUk0cnlCc2toY1hlIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDEwXC9zZXJ2aWNlcyIsInJvdXRlIjoid2Vic2l0ZS5zZXJ2aWNlcyJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19	1783244219
hJmH900dKuY6E6DDKrTs2Z52eK3p6DTjjaNr2Xe9	\N	127.0.0.1		eyJfdG9rZW4iOiJGUnFUdDJBd0JNTDVlU0ZXdkR3am9vYk1kRkJOSHdNdXFuaXdKRllRIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDEwXC93b3JrIiwicm91dGUiOiJ3ZWJzaXRlLndvcmsifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==	1783244219
RLwL3h1xHYKcmkDBWTlNs6RJet78hXFSVgohvNan	\N	127.0.0.1		eyJfdG9rZW4iOiJRcnRDa0JOSHB2d3BNQzZzRTZUTTdSZG5oYXBPME95WmdHVDV3U3U4IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDEwXC90ZWFtIiwicm91dGUiOiJ3ZWJzaXRlLnRlYW0ifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==	1783244219
RaNoxI654bJTnzgW4HWhYIJlS14UnapAc5S92Bst	\N	127.0.0.1		eyJfdG9rZW4iOiJsZ055SzNKTmtNUWxuYTVEZlBFV1h2dWNadnBWUWJYUTIxSGNNUnJiIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDEwXC9jbGllbnRzIiwicm91dGUiOiJ3ZWJzaXRlLmNsaWVudHMifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==	1783244219
eYxFClARtfIqVt0dePwdWC58EKgrZTb8UIgL9aXe	\N	127.0.0.1		eyJfdG9rZW4iOiI5OFRTM3RZc29vY3dMUGJWMDRnWWR3VXBjSWpzb0RIaThWWFdRRVR2IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDEwXC9jb250YWN0Iiwicm91dGUiOiJ3ZWJzaXRlLmNvbnRhY3QifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==	1783244219
1xqT1A3Lp5KCnjtIKWZBI97XCDWiIhp85MM2beCd	\N	127.0.0.1		eyJfdG9rZW4iOiI4STNINTNMV2txOXBkUHdGV2dZbkwwYjBzUXFTNkVoblBKQWNzNTNkIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDEwXC9jbGllbnRcL2xvZ2luIiwicm91dGUiOiJjbGllbnQubG9naW4ifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==	1783244219
r6QyM53zWTPiGlJPV7Tz1egMb7ABXWsrFBnTHBbF	\N	127.0.0.1		eyJfdG9rZW4iOiJEMkNUaXRNaWZjM3FVNlFFSWk2Vmx2R0hTS1QxOVBhdXExOWNnTWY2IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDEwXC9lbXBsb3llZVwvbG9naW4iLCJyb3V0ZSI6ImVtcGxveWVlLmxvZ2luIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=	1783244219
0Kea7vd4OnDmrRd4lc0UnwhDtj8pcE2RzyK2v6uT	\N	127.0.0.1		eyJfdG9rZW4iOiJKcmRHbU9kVE03RGxwNHBRSHZPRmVidDdlWDJBRmNMRVBnakxjc3lNIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDEwIiwicm91dGUiOiJ3ZWJzaXRlLmhvbWUifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==	1783244269
OAlfteIRHAT6PrCGJNRR17JZucyxF6XPh2ZRNNDj	\N	127.0.0.1		eyJfdG9rZW4iOiI5eW1ydWtqakN5MUR3dWZNWkhSdUNvUm8xbFJ0Q05HZzEyWEpscnBLIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDEwXC9hYm91dCIsInJvdXRlIjoid2Vic2l0ZS5hYm91dCJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19	1783244269
6oDYfgdldzlscGWTd1d7qQ0FxcLuwncY9pzCrEuO	\N	127.0.0.1		eyJfdG9rZW4iOiIycXlCZzIzUm90RFFjWklHMXp1WUtvSEVyZ3RNcEVuT0ZKUlE4ZzQ3IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDEwXC9zZXJ2aWNlcyIsInJvdXRlIjoid2Vic2l0ZS5zZXJ2aWNlcyJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19	1783244269
8DnUDeb3ebEQwop8apV51XRnxVaef3pSLgNTD431	\N	127.0.0.1		eyJfdG9rZW4iOiJZR2ZpNDJ6TXIxelY4NU4wZWpyZjJaY0N1RTRzOXBLSUdUaHBVdHhwIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDEwXC93b3JrIiwicm91dGUiOiJ3ZWJzaXRlLndvcmsifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==	1783244269
J4MDfIPwDK67wzyVcMhUCfs6xMV39C1NlgMJ5zdc	\N	127.0.0.1		eyJfdG9rZW4iOiJBSFRrSXBmN0I1VFJnck5hOHR4YTFlbkpIMmpoVEE3b0lmTGp5SzVtIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDEwXC90ZWFtIiwicm91dGUiOiJ3ZWJzaXRlLnRlYW0ifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==	1783244269
HT3hR1rZQKZvA7n7eYi0J9sid5ysLkEjSRYLR7k4	\N	127.0.0.1		eyJfdG9rZW4iOiJQVmFLeDk0UVpWeHFobG1FZjBHVGNoUWRGeTA5WkU3RUM5bG8wd2MyIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDEwXC9jbGllbnRzIiwicm91dGUiOiJ3ZWJzaXRlLmNsaWVudHMifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==	1783244270
tchnyt7L2GnFQZYndLtbYlSbXUqEkybEfCsJIe5V	\N	127.0.0.1		eyJfdG9rZW4iOiJZY0Q2TndOUEE0M2x3c3BMRWRZZUxnQkZSM3haOFFqeDF4NkF2d1A5IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDEwXC9jb250YWN0Iiwicm91dGUiOiJ3ZWJzaXRlLmNvbnRhY3QifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==	1783244270
dLPsNRNH15EWPE2DTgjbu4skkSnu5W0Vmt1ZSkhl	\N	127.0.0.1		eyJfdG9rZW4iOiIxZ21NSUhheVByeEgxTnA0ZXRBb2tSWFAyOHV6UXhMSDduMXg2azJhIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDEwXC9jbGllbnRcL2xvZ2luIiwicm91dGUiOiJjbGllbnQubG9naW4ifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==	1783244270
XQQ32waJexNlqLwg5UaPMz064AjTiKcrUEKNxRLX	\N	127.0.0.1		eyJfdG9rZW4iOiJDQzlpdHBQbGlEOEdPOENlYzcxYWlESFNpdXY5TTRSMG91bFMydGlrIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDEwXC9lbXBsb3llZVwvbG9naW4iLCJyb3V0ZSI6ImVtcGxveWVlLmxvZ2luIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=	1783244270
Buk07oAlprfcwBaSE17calZCYaJOnRIxuVqtQQ3V	\N	127.0.0.1		eyJfdG9rZW4iOiIwUnBaOXlpTVQ0Z1Z3MzdOdXpQOUphWlNEQm5TcjZ3YUI5UTBYdjJPIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDEwXC93b3JrIiwicm91dGUiOiJ3ZWJzaXRlLndvcmsifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==	1783244918
C8EiKqYhGsbciuBiARzvzWdmakw91tFVA3CXuAWM	\N	127.0.0.1		eyJfdG9rZW4iOiIyb2E2VWIwRmdLeUg5b3Vra1dCcEhlU1J4QnlkSHljcUxnTFJOem9UIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDEwXC90ZWFtIiwicm91dGUiOiJ3ZWJzaXRlLnRlYW0ifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==	1783244918
sxACu3HEmXnRR1qanzG5BSyHrASY8wb42v5M3xA6	\N	127.0.0.1		eyJfdG9rZW4iOiJYaWZTRVpzRDZpRkVRMWhCNWtDMEN3NFV2ZG9DaVU1OGl1NzN2MGRmIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDEwXC9zZXJ2aWNlcyIsInJvdXRlIjoid2Vic2l0ZS5zZXJ2aWNlcyJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19	1783244918
t3cSccx9ZHthHQVWyNUztDVoomDE9u54nqqXRyA1	2	127.0.0.1	Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15	eyJfdG9rZW4iOiJORjNJNklCdUEyQ2dyWVZ4ZEVFN2FaQ2JGQkp3Q1B6ODBaNW1KU2xIIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL2Rhc2hib2FyZCIsInJvdXRlIjoiZGFzaGJvYXJkIn0sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjoyfQ==	1783252657
mYO7f1cwLJ2NAmawunF0zgJarLbM0nYzlA0nn1BK	\N	127.0.0.1	Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36	eyJfdG9rZW4iOiJBaXVlalNkZnlWUVRZZENnSXpLMmlTeEpGWHJnUUdmd2V1SHlTR2t1IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDEwXC9sb2dpbiIsInJvdXRlIjoibG9naW4ifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJ1cmwiOnsiaW50ZW5kZWQiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAxMFwvYWRtaW5cL2NsaWVudC1yZXF1ZXN0cyJ9LCJsb2dpbl9jbGllbnRfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MX0=	1783249130
QAaAaZ3R0jDwRJKPo6WrBz75qEdu8hNY0cpDuyig	\N	127.0.0.1	curl/8.7.1	eyJfdG9rZW4iOiJPQllQTWNjZTBBNExkOGJpS3JhNDN2WVMzUHlHbWJXbTlXYWpzdm4yIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==	1783253003
2S1ySwTIu3vVJZ2Ox3Lkm3gRz9LYQqQnymiHh6J4	\N	127.0.0.1	curl/8.7.1	eyJfdG9rZW4iOiJvSE5ZdkJoYmVqVWc2dnlNUG5iTjlSM1ZuMWNUZG50V0tmd3hKa1NBIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==	1783253003
dhFCIT6rHoa2Bs0ED1XTopCiTbLw4NNITf02WVI5	\N	127.0.0.1	curl/8.7.1	eyJfdG9rZW4iOiJRbEhKaE1rRUJKOGtJYTg0b1h0eGNlZVM2NzR3ajNwTk1WRnBSUndPIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==	1783253003
9bbgCQHUX2biJMpRtR5eP6DrvKxFpSDPklX8ywgo	\N	127.0.0.1		eyJfdG9rZW4iOiJsNVdwZllTNTNrVU16VEhFR1YyOFZsQ3cxNUFYejhJUjhaV3FHSGR0IiwidXJsIjp7ImludGVuZGVkIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMTBcL2FpLWVtcGxveWVlIn0sIl9wcmV2aW91cyI6eyJ1cmwiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAxMFwvYWktZW1wbG95ZWUiLCJyb3V0ZSI6ImFpLWVtcGxveWVlLmluZGV4In0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=	1783250196
489jBgQx8rCaL3ggIdmr3TRcUFmRW9q0gissgPi2	\N	127.0.0.1		eyJfdG9rZW4iOiJMcE9xQW4wcVVvV3Z1Yk1XWlAxdlRiYnlSRnJTcXJUeWNaZjRVeERyIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDEwXC9sb2dpbiIsInJvdXRlIjoibG9naW4ifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==	1783250196
RVfUGb5sd2JSitM4pO2RKAIf7NVMrohBnppzxt0d	1	127.0.0.1	Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36	eyJfdG9rZW4iOiJhdXZZYXVtRjAzemRISHNYb05XNWNBZXlEem41dUxsOUVtbERvUWpsIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9kYXNoYm9hcmQiLCJyb3V0ZSI6ImRhc2hib2FyZCJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX0sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjoxfQ==	1783253169
29Svz1MccNVT30uEZmS2C31IOpT39bXHEh63VDDr	\N	127.0.0.1	Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15	eyJfdG9rZW4iOiI3VTZ3WjhaZDZ0eFQ1MmhWclRkT3ZvbVhxbU5GTXJVR3UyUmtxeDlpIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAxIiwicm91dGUiOm51bGx9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19	1783251764
PSg1Jmdbaav9SHaEienuVBG5QZoaYh5PS4jDYdx1	2	127.0.0.1	Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36	eyJfdG9rZW4iOiJpY3RCQncyMnhkemI4akNNQWZtdDlOczdJc0JMaUZmNVJqR2VYdm9uIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMTBcL2NybSIsInJvdXRlIjoiY3JtLmluZGV4In0sImxvZ2luX2NsaWVudF81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjozLCJ1cmwiOltdLCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6Mn0=	1783251614
mSUJ2IBx2gKeDbLSgbiJKZ7TqrR8nuLsAIOrRC1Q	\N	127.0.0.1		eyJfdG9rZW4iOiJNcEJWRWxKM2lhZFB1SGk2aEp3NjlEU1VFdHhYVjYzaDJVU3VhejczIiwidXJsIjp7ImludGVuZGVkIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMTBcL2FkbWluXC9jbGllbnQtcmVxdWVzdHMifSwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDEwXC9hZG1pblwvY2xpZW50LXJlcXVlc3RzIiwicm91dGUiOiJhZG1pbi5jbGllbnQtcmVxdWVzdHMuaW5kZXgifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==	1783249075
sq7PkSiMTgXDfKPUy3l6p6N8X4N75d1R5Gq7dPfs	\N	127.0.0.1	curl/8.7.1	eyJfdG9rZW4iOiJLUWJuZkoxWDdiWGFtZGV4a1N3MjZteUQ1c3ptd1BhRW15eGFlbWp1IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDEwXC9jbGllbnRcL2xvZ2luIiwicm91dGUiOiJjbGllbnQubG9naW4ifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==	1783253745
CuZCUMC4HRaRDXw9nAZ06E9HZlk9FtZykhf1CdOk	\N	127.0.0.1	curl/8.7.1	eyJfdG9rZW4iOiJ2YWZITEo0MXBPT0M4aFhXVXZIOFEzTkNUMVRha3lwNjB6ZElTMThDIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDEwXC9lbXBsb3llZVwvbG9naW4iLCJyb3V0ZSI6ImVtcGxveWVlLmxvZ2luIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=	1783253745
DEGShGupHiR00GTPh1vkV3vP35wTv2IrruAKqxuL	\N	127.0.0.1	curl/8.7.1	eyJfdG9rZW4iOiJ4QlVIYjhGOGN3Q2lPNTE2OUF0bWpJODd3S040N2RnVHdyS2xGbmVFIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDEwXC93b3JrIiwicm91dGUiOiJ3ZWJzaXRlLndvcmsifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==	1783253745
KkYCtIzfCauxe7rBTCjzjDR5CkXH7zOqoBWYsBrJ	2	127.0.0.1	Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36	eyJfdG9rZW4iOiJjcktGR2lVSkd6amFJbHd5cXd3ZWRCUHFxZHZRVkVqbWtFTUpDeDFUIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9hZG1pblwvcHJvamVjdHMiLCJyb3V0ZSI6ImFkbWluLnByb2plY3RzLmluZGV4In0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjJ9	1783253792
\.


--
-- Data for Name: settings; Type: TABLE DATA; Schema: public; Owner: mziyad
--

COPY public.settings (id, key, value, "group", type, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: support_messages; Type: TABLE DATA; Schema: public; Owner: mziyad
--

COPY public.support_messages (id, ticket_id, user_id, sender_type, message, meta, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: support_tickets; Type: TABLE DATA; Schema: public; Owner: mziyad
--

COPY public.support_tickets (id, ticket_number, client_id, company_id, assigned_to, subject, status, priority, channel, ai_summary, closed_at, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: teams; Type: TABLE DATA; Schema: public; Owner: mziyad
--

COPY public.teams (id, name, code, description, is_active, created_at, updated_at) FROM stdin;
1	Traffic Team	TRAFFIC	Traffic Management Team	t	\N	\N
2	Design Team	DESIGN	Graphic Design Team	t	\N	\N
3	Content Team	CONTENT	Content Creation Team	t	\N	\N
4	Motion Team	MOTION	Motion Graphics Team	t	\N	\N
5	QA Team	QA	Quality Assurance Team	t	\N	\N
6	Archive Team	ARCHIVE	Archive and Storage Team	t	\N	\N
7	Customer Service Team	CS	Customer Service Team	t	\N	\N
\.


--
-- Data for Name: user_outlook_accounts; Type: TABLE DATA; Schema: public; Owner: mziyad
--

COPY public.user_outlook_accounts (id, user_id, microsoft_user_id, email, display_name, access_token, refresh_token, scopes, token_expires_at, is_connected, connected_at, disconnected_at, last_synced_at, last_error, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: mziyad
--

COPY public.users (id, name, email, email_verified_at, password, remember_token, created_at, updated_at, employee_no, branch_id, team_id, role_id, job_title, mobile, capacity_hours, is_active) FROM stdin;
2	Monther	mziyad@pgintegrated.com	\N	$2y$12$gEXOYY6eRIy4tEbdLsI1cuVSbEAUQtVvZvsPXCCk.Lh8KCashBNzy	\N	2026-07-02 10:13:51	2026-07-02 10:13:51	300	1	1	1	I.T Manager	0531910792	12	t
1	Monther Ziyad	monther.ziad08@gmail.com	\N	$2y$12$90KkFiaAkI4MWiE7Oa8LkeeODlHC70KGSnEio/sZVtc4T9JL82fUu	6SP46lQs1iMbSVqp4Ylr8b0X5euh3lPCeNeECaM0dWaOB90A2Ae8FCfVJJGb	2026-06-30 11:30:04	2026-06-30 11:30:04	\N	\N	\N	\N	\N	\N	8	t
\.


--
-- Data for Name: workflow_stages; Type: TABLE DATA; Schema: public; Owner: mziyad
--

COPY public.workflow_stages (id, name, code, description, sort_order, color, icon, is_start, is_end, requires_approval, allow_file_upload, allow_comments, is_active, created_at, updated_at) FROM stdin;
1	Email Intake	EMAIL	Incoming email from Outlook	1	#3B82F6	mail	t	f	f	t	t	t	\N	\N
2	Traffic	TRAFFIC	Traffic Department	2	#0EA5E9	route	f	f	f	t	t	t	\N	\N
3	Content	CONTENT	Content Writing	3	#6366F1	pen	f	f	f	t	t	t	\N	\N
4	Design	DESIGN	Graphic Design	4	#8B5CF6	palette	f	f	f	t	t	t	\N	\N
5	Motion	MOTION	Motion Graphics	5	#EC4899	film	f	f	f	t	t	t	\N	\N
6	QA Review	QA	Quality Assurance	6	#F59E0B	shield-check	f	f	t	t	t	t	\N	\N
7	Client Review	CLIENT	Waiting Client Approval	7	#F97316	user-check	f	f	t	t	t	t	\N	\N
8	Revision	REVISION	Client Revision	8	#EF4444	refresh-cw	f	f	f	t	t	t	\N	\N
9	Completed	COMPLETED	Completed Successfully	9	#22C55E	check-circle	f	f	f	t	t	t	\N	\N
10	Archive	ARCHIVE	Archived Job	10	#64748B	archive	f	t	f	f	f	t	\N	\N
\.


--
-- Data for Name: workflow_transitions; Type: TABLE DATA; Schema: public; Owner: mziyad
--

COPY public.workflow_transitions (id, from_stage_id, to_stage_id, name, code, requires_permission, required_permission, requires_comment, requires_file, is_active, created_at, updated_at) FROM stdin;
1	1	2	Email → Traffic	EMAIL_TRAFFIC	f	\N	f	f	t	\N	\N
2	2	3	Traffic → Content	TRAFFIC_CONTENT	f	\N	f	f	t	\N	\N
3	2	4	Traffic → Design	TRAFFIC_DESIGN	f	\N	f	f	t	\N	\N
4	2	5	Traffic → Motion	TRAFFIC_MOTION	f	\N	f	f	t	\N	\N
5	3	4	Content → Design	CONTENT_DESIGN	f	\N	f	f	t	\N	\N
6	4	6	Design → QA	DESIGN_QA	f	\N	f	t	t	\N	\N
7	5	6	Motion → QA	MOTION_QA	f	\N	f	t	t	\N	\N
8	6	7	QA → Client Review	QA_CLIENT	t	approve_design	f	f	t	\N	\N
9	7	8	Client → Revision	CLIENT_REVISION	f	\N	t	f	t	\N	\N
10	8	4	Revision → Design	REVISION_DESIGN	f	\N	t	f	t	\N	\N
11	7	9	Client → Completed	CLIENT_COMPLETED	t	complete_job	f	f	t	\N	\N
12	9	10	Completed → Archive	COMPLETED_ARCHIVE	f	\N	f	f	t	\N	\N
\.


--
-- Name: ai_approval_suggestions_id_seq; Type: SEQUENCE SET; Schema: public; Owner: mziyad
--

SELECT pg_catalog.setval('public.ai_approval_suggestions_id_seq', 1, false);


--
-- Name: ai_interactions_id_seq; Type: SEQUENCE SET; Schema: public; Owner: mziyad
--

SELECT pg_catalog.setval('public.ai_interactions_id_seq', 1, false);


--
-- Name: assets_id_seq; Type: SEQUENCE SET; Schema: public; Owner: mziyad
--

SELECT pg_catalog.setval('public.assets_id_seq', 2, true);


--
-- Name: branches_id_seq; Type: SEQUENCE SET; Schema: public; Owner: mziyad
--

SELECT pg_catalog.setval('public.branches_id_seq', 6, true);


--
-- Name: client_project_requests_id_seq; Type: SEQUENCE SET; Schema: public; Owner: mziyad
--

SELECT pg_catalog.setval('public.client_project_requests_id_seq', 1, false);


--
-- Name: clients_id_seq; Type: SEQUENCE SET; Schema: public; Owner: mziyad
--

SELECT pg_catalog.setval('public.clients_id_seq', 3, true);


--
-- Name: cms_pages_id_seq; Type: SEQUENCE SET; Schema: public; Owner: mziyad
--

SELECT pg_catalog.setval('public.cms_pages_id_seq', 7, true);


--
-- Name: creative_jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: mziyad
--

SELECT pg_catalog.setval('public.creative_jobs_id_seq', 5, true);


--
-- Name: crm_activities_id_seq; Type: SEQUENCE SET; Schema: public; Owner: mziyad
--

SELECT pg_catalog.setval('public.crm_activities_id_seq', 1, false);


--
-- Name: crm_companies_id_seq; Type: SEQUENCE SET; Schema: public; Owner: mziyad
--

SELECT pg_catalog.setval('public.crm_companies_id_seq', 1, false);


--
-- Name: crm_contacts_id_seq; Type: SEQUENCE SET; Schema: public; Owner: mziyad
--

SELECT pg_catalog.setval('public.crm_contacts_id_seq', 1, false);


--
-- Name: crm_tasks_id_seq; Type: SEQUENCE SET; Schema: public; Owner: mziyad
--

SELECT pg_catalog.setval('public.crm_tasks_id_seq', 1, false);


--
-- Name: email_intake_attachments_id_seq; Type: SEQUENCE SET; Schema: public; Owner: mziyad
--

SELECT pg_catalog.setval('public.email_intake_attachments_id_seq', 1, false);


--
-- Name: email_intake_traffic_members_id_seq; Type: SEQUENCE SET; Schema: public; Owner: mziyad
--

SELECT pg_catalog.setval('public.email_intake_traffic_members_id_seq', 1, false);


--
-- Name: email_intake_validations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: mziyad
--

SELECT pg_catalog.setval('public.email_intake_validations_id_seq', 1, false);


--
-- Name: email_intakes_id_seq; Type: SEQUENCE SET; Schema: public; Owner: mziyad
--

SELECT pg_catalog.setval('public.email_intakes_id_seq', 1, false);


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: mziyad
--

SELECT pg_catalog.setval('public.failed_jobs_id_seq', 1, false);


--
-- Name: job_activities_id_seq; Type: SEQUENCE SET; Schema: public; Owner: mziyad
--

SELECT pg_catalog.setval('public.job_activities_id_seq', 3, true);


--
-- Name: job_assignments_id_seq; Type: SEQUENCE SET; Schema: public; Owner: mziyad
--

SELECT pg_catalog.setval('public.job_assignments_id_seq', 5, true);


--
-- Name: job_categories_id_seq; Type: SEQUENCE SET; Schema: public; Owner: mziyad
--

SELECT pg_catalog.setval('public.job_categories_id_seq', 1, false);


--
-- Name: job_stage_histories_id_seq; Type: SEQUENCE SET; Schema: public; Owner: mziyad
--

SELECT pg_catalog.setval('public.job_stage_histories_id_seq', 1, false);


--
-- Name: job_statuses_id_seq; Type: SEQUENCE SET; Schema: public; Owner: mziyad
--

SELECT pg_catalog.setval('public.job_statuses_id_seq', 1, false);


--
-- Name: jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: mziyad
--

SELECT pg_catalog.setval('public.jobs_id_seq', 1, false);


--
-- Name: marketing_campaign_recipients_id_seq; Type: SEQUENCE SET; Schema: public; Owner: mziyad
--

SELECT pg_catalog.setval('public.marketing_campaign_recipients_id_seq', 1, false);


--
-- Name: marketing_campaigns_id_seq; Type: SEQUENCE SET; Schema: public; Owner: mziyad
--

SELECT pg_catalog.setval('public.marketing_campaigns_id_seq', 1, false);


--
-- Name: migrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: mziyad
--

SELECT pg_catalog.setval('public.migrations_id_seq', 35, true);


--
-- Name: projects_id_seq; Type: SEQUENCE SET; Schema: public; Owner: mziyad
--

SELECT pg_catalog.setval('public.projects_id_seq', 3, true);


--
-- Name: revisions_id_seq; Type: SEQUENCE SET; Schema: public; Owner: mziyad
--

SELECT pg_catalog.setval('public.revisions_id_seq', 1, false);


--
-- Name: roles_id_seq; Type: SEQUENCE SET; Schema: public; Owner: mziyad
--

SELECT pg_catalog.setval('public.roles_id_seq', 11, true);


--
-- Name: settings_id_seq; Type: SEQUENCE SET; Schema: public; Owner: mziyad
--

SELECT pg_catalog.setval('public.settings_id_seq', 1, false);


--
-- Name: support_messages_id_seq; Type: SEQUENCE SET; Schema: public; Owner: mziyad
--

SELECT pg_catalog.setval('public.support_messages_id_seq', 1, false);


--
-- Name: support_tickets_id_seq; Type: SEQUENCE SET; Schema: public; Owner: mziyad
--

SELECT pg_catalog.setval('public.support_tickets_id_seq', 1, false);


--
-- Name: teams_id_seq; Type: SEQUENCE SET; Schema: public; Owner: mziyad
--

SELECT pg_catalog.setval('public.teams_id_seq', 7, true);


--
-- Name: user_outlook_accounts_id_seq; Type: SEQUENCE SET; Schema: public; Owner: mziyad
--

SELECT pg_catalog.setval('public.user_outlook_accounts_id_seq', 1, false);


--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: mziyad
--

SELECT pg_catalog.setval('public.users_id_seq', 2, true);


--
-- Name: workflow_stages_id_seq; Type: SEQUENCE SET; Schema: public; Owner: mziyad
--

SELECT pg_catalog.setval('public.workflow_stages_id_seq', 10, true);


--
-- Name: workflow_transitions_id_seq; Type: SEQUENCE SET; Schema: public; Owner: mziyad
--

SELECT pg_catalog.setval('public.workflow_transitions_id_seq', 14, true);


--
-- Name: ai_approval_suggestions ai_approval_suggestions_pkey; Type: CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.ai_approval_suggestions
    ADD CONSTRAINT ai_approval_suggestions_pkey PRIMARY KEY (id);


--
-- Name: ai_interactions ai_interactions_pkey; Type: CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.ai_interactions
    ADD CONSTRAINT ai_interactions_pkey PRIMARY KEY (id);


--
-- Name: assets assets_pkey; Type: CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.assets
    ADD CONSTRAINT assets_pkey PRIMARY KEY (id);


--
-- Name: branches branches_code_unique; Type: CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.branches
    ADD CONSTRAINT branches_code_unique UNIQUE (code);


--
-- Name: branches branches_pkey; Type: CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.branches
    ADD CONSTRAINT branches_pkey PRIMARY KEY (id);


--
-- Name: cache_locks cache_locks_pkey; Type: CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.cache_locks
    ADD CONSTRAINT cache_locks_pkey PRIMARY KEY (key);


--
-- Name: cache cache_pkey; Type: CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.cache
    ADD CONSTRAINT cache_pkey PRIMARY KEY (key);


--
-- Name: client_project_requests client_project_requests_pkey; Type: CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.client_project_requests
    ADD CONSTRAINT client_project_requests_pkey PRIMARY KEY (id);


--
-- Name: client_project_requests client_project_requests_request_number_unique; Type: CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.client_project_requests
    ADD CONSTRAINT client_project_requests_request_number_unique UNIQUE (request_number);


--
-- Name: clients clients_client_code_unique; Type: CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.clients
    ADD CONSTRAINT clients_client_code_unique UNIQUE (client_code);


--
-- Name: clients clients_pkey; Type: CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.clients
    ADD CONSTRAINT clients_pkey PRIMARY KEY (id);


--
-- Name: cms_pages cms_pages_key_unique; Type: CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.cms_pages
    ADD CONSTRAINT cms_pages_key_unique UNIQUE (key);


--
-- Name: cms_pages cms_pages_pkey; Type: CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.cms_pages
    ADD CONSTRAINT cms_pages_pkey PRIMARY KEY (id);


--
-- Name: cms_pages cms_pages_slug_unique; Type: CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.cms_pages
    ADD CONSTRAINT cms_pages_slug_unique UNIQUE (slug);


--
-- Name: creative_jobs creative_jobs_job_number_unique; Type: CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.creative_jobs
    ADD CONSTRAINT creative_jobs_job_number_unique UNIQUE (job_number);


--
-- Name: creative_jobs creative_jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.creative_jobs
    ADD CONSTRAINT creative_jobs_pkey PRIMARY KEY (id);


--
-- Name: crm_activities crm_activities_pkey; Type: CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.crm_activities
    ADD CONSTRAINT crm_activities_pkey PRIMARY KEY (id);


--
-- Name: crm_companies crm_companies_pkey; Type: CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.crm_companies
    ADD CONSTRAINT crm_companies_pkey PRIMARY KEY (id);


--
-- Name: crm_contacts crm_contacts_pkey; Type: CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.crm_contacts
    ADD CONSTRAINT crm_contacts_pkey PRIMARY KEY (id);


--
-- Name: crm_tasks crm_tasks_pkey; Type: CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.crm_tasks
    ADD CONSTRAINT crm_tasks_pkey PRIMARY KEY (id);


--
-- Name: email_intake_attachments email_intake_attachments_pkey; Type: CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.email_intake_attachments
    ADD CONSTRAINT email_intake_attachments_pkey PRIMARY KEY (id);


--
-- Name: email_intake_traffic_members email_intake_traffic_members_pkey; Type: CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.email_intake_traffic_members
    ADD CONSTRAINT email_intake_traffic_members_pkey PRIMARY KEY (id);


--
-- Name: email_intake_traffic_members email_intake_traffic_members_user_id_unique; Type: CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.email_intake_traffic_members
    ADD CONSTRAINT email_intake_traffic_members_user_id_unique UNIQUE (user_id);


--
-- Name: email_intake_validations email_intake_validations_pkey; Type: CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.email_intake_validations
    ADD CONSTRAINT email_intake_validations_pkey PRIMARY KEY (id);


--
-- Name: email_intakes email_intakes_message_id_unique; Type: CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.email_intakes
    ADD CONSTRAINT email_intakes_message_id_unique UNIQUE (message_id);


--
-- Name: email_intakes email_intakes_pkey; Type: CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.email_intakes
    ADD CONSTRAINT email_intakes_pkey PRIMARY KEY (id);


--
-- Name: failed_jobs failed_jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_pkey PRIMARY KEY (id);


--
-- Name: failed_jobs failed_jobs_uuid_unique; Type: CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_uuid_unique UNIQUE (uuid);


--
-- Name: job_activities job_activities_pkey; Type: CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.job_activities
    ADD CONSTRAINT job_activities_pkey PRIMARY KEY (id);


--
-- Name: job_assignments job_assignments_pkey; Type: CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.job_assignments
    ADD CONSTRAINT job_assignments_pkey PRIMARY KEY (id);


--
-- Name: job_batches job_batches_pkey; Type: CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.job_batches
    ADD CONSTRAINT job_batches_pkey PRIMARY KEY (id);


--
-- Name: job_categories job_categories_code_unique; Type: CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.job_categories
    ADD CONSTRAINT job_categories_code_unique UNIQUE (code);


--
-- Name: job_categories job_categories_pkey; Type: CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.job_categories
    ADD CONSTRAINT job_categories_pkey PRIMARY KEY (id);


--
-- Name: job_stage_histories job_stage_histories_pkey; Type: CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.job_stage_histories
    ADD CONSTRAINT job_stage_histories_pkey PRIMARY KEY (id);


--
-- Name: job_statuses job_statuses_code_unique; Type: CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.job_statuses
    ADD CONSTRAINT job_statuses_code_unique UNIQUE (code);


--
-- Name: job_statuses job_statuses_pkey; Type: CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.job_statuses
    ADD CONSTRAINT job_statuses_pkey PRIMARY KEY (id);


--
-- Name: jobs jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.jobs
    ADD CONSTRAINT jobs_pkey PRIMARY KEY (id);


--
-- Name: marketing_campaign_recipients marketing_campaign_recipients_pkey; Type: CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.marketing_campaign_recipients
    ADD CONSTRAINT marketing_campaign_recipients_pkey PRIMARY KEY (id);


--
-- Name: marketing_campaigns marketing_campaigns_pkey; Type: CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.marketing_campaigns
    ADD CONSTRAINT marketing_campaigns_pkey PRIMARY KEY (id);


--
-- Name: migrations migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);


--
-- Name: password_reset_tokens password_reset_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.password_reset_tokens
    ADD CONSTRAINT password_reset_tokens_pkey PRIMARY KEY (email);


--
-- Name: projects projects_pkey; Type: CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.projects
    ADD CONSTRAINT projects_pkey PRIMARY KEY (id);


--
-- Name: projects projects_project_code_unique; Type: CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.projects
    ADD CONSTRAINT projects_project_code_unique UNIQUE (project_code);


--
-- Name: revisions revisions_pkey; Type: CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.revisions
    ADD CONSTRAINT revisions_pkey PRIMARY KEY (id);


--
-- Name: roles roles_code_unique; Type: CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_code_unique UNIQUE (code);


--
-- Name: roles roles_pkey; Type: CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_pkey PRIMARY KEY (id);


--
-- Name: sessions sessions_pkey; Type: CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.sessions
    ADD CONSTRAINT sessions_pkey PRIMARY KEY (id);


--
-- Name: settings settings_key_unique; Type: CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.settings
    ADD CONSTRAINT settings_key_unique UNIQUE (key);


--
-- Name: settings settings_pkey; Type: CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.settings
    ADD CONSTRAINT settings_pkey PRIMARY KEY (id);


--
-- Name: support_messages support_messages_pkey; Type: CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.support_messages
    ADD CONSTRAINT support_messages_pkey PRIMARY KEY (id);


--
-- Name: support_tickets support_tickets_pkey; Type: CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.support_tickets
    ADD CONSTRAINT support_tickets_pkey PRIMARY KEY (id);


--
-- Name: support_tickets support_tickets_ticket_number_unique; Type: CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.support_tickets
    ADD CONSTRAINT support_tickets_ticket_number_unique UNIQUE (ticket_number);


--
-- Name: teams teams_code_unique; Type: CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.teams
    ADD CONSTRAINT teams_code_unique UNIQUE (code);


--
-- Name: teams teams_pkey; Type: CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.teams
    ADD CONSTRAINT teams_pkey PRIMARY KEY (id);


--
-- Name: user_outlook_accounts user_outlook_accounts_microsoft_user_id_unique; Type: CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.user_outlook_accounts
    ADD CONSTRAINT user_outlook_accounts_microsoft_user_id_unique UNIQUE (microsoft_user_id);


--
-- Name: user_outlook_accounts user_outlook_accounts_pkey; Type: CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.user_outlook_accounts
    ADD CONSTRAINT user_outlook_accounts_pkey PRIMARY KEY (id);


--
-- Name: user_outlook_accounts user_outlook_accounts_user_id_unique; Type: CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.user_outlook_accounts
    ADD CONSTRAINT user_outlook_accounts_user_id_unique UNIQUE (user_id);


--
-- Name: users users_email_unique; Type: CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_unique UNIQUE (email);


--
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: workflow_stages workflow_stages_code_unique; Type: CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.workflow_stages
    ADD CONSTRAINT workflow_stages_code_unique UNIQUE (code);


--
-- Name: workflow_stages workflow_stages_pkey; Type: CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.workflow_stages
    ADD CONSTRAINT workflow_stages_pkey PRIMARY KEY (id);


--
-- Name: workflow_transitions workflow_transitions_code_unique; Type: CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.workflow_transitions
    ADD CONSTRAINT workflow_transitions_code_unique UNIQUE (code);


--
-- Name: workflow_transitions workflow_transitions_pkey; Type: CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.workflow_transitions
    ADD CONSTRAINT workflow_transitions_pkey PRIMARY KEY (id);


--
-- Name: ai_approval_suggestions_subject_type_subject_id_index; Type: INDEX; Schema: public; Owner: mziyad
--

CREATE INDEX ai_approval_suggestions_subject_type_subject_id_index ON public.ai_approval_suggestions USING btree (subject_type, subject_id);


--
-- Name: ai_interactions_subject_type_subject_id_index; Type: INDEX; Schema: public; Owner: mziyad
--

CREATE INDEX ai_interactions_subject_type_subject_id_index ON public.ai_interactions USING btree (subject_type, subject_id);


--
-- Name: cache_expiration_index; Type: INDEX; Schema: public; Owner: mziyad
--

CREATE INDEX cache_expiration_index ON public.cache USING btree (expiration);


--
-- Name: cache_locks_expiration_index; Type: INDEX; Schema: public; Owner: mziyad
--

CREATE INDEX cache_locks_expiration_index ON public.cache_locks USING btree (expiration);


--
-- Name: failed_jobs_connection_queue_failed_at_index; Type: INDEX; Schema: public; Owner: mziyad
--

CREATE INDEX failed_jobs_connection_queue_failed_at_index ON public.failed_jobs USING btree (connection, queue, failed_at);


--
-- Name: jobs_queue_index; Type: INDEX; Schema: public; Owner: mziyad
--

CREATE INDEX jobs_queue_index ON public.jobs USING btree (queue);


--
-- Name: sessions_last_activity_index; Type: INDEX; Schema: public; Owner: mziyad
--

CREATE INDEX sessions_last_activity_index ON public.sessions USING btree (last_activity);


--
-- Name: sessions_user_id_index; Type: INDEX; Schema: public; Owner: mziyad
--

CREATE INDEX sessions_user_id_index ON public.sessions USING btree (user_id);


--
-- Name: user_outlook_accounts_email_index; Type: INDEX; Schema: public; Owner: mziyad
--

CREATE INDEX user_outlook_accounts_email_index ON public.user_outlook_accounts USING btree (email);


--
-- Name: user_outlook_accounts_is_connected_index; Type: INDEX; Schema: public; Owner: mziyad
--

CREATE INDEX user_outlook_accounts_is_connected_index ON public.user_outlook_accounts USING btree (is_connected);


--
-- Name: ai_approval_suggestions ai_approval_suggestions_approved_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.ai_approval_suggestions
    ADD CONSTRAINT ai_approval_suggestions_approved_by_foreign FOREIGN KEY (approved_by) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- Name: ai_approval_suggestions ai_approval_suggestions_created_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.ai_approval_suggestions
    ADD CONSTRAINT ai_approval_suggestions_created_by_foreign FOREIGN KEY (created_by) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- Name: ai_interactions ai_interactions_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.ai_interactions
    ADD CONSTRAINT ai_interactions_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- Name: assets assets_creative_job_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.assets
    ADD CONSTRAINT assets_creative_job_id_foreign FOREIGN KEY (creative_job_id) REFERENCES public.creative_jobs(id) ON DELETE CASCADE;


--
-- Name: assets assets_uploaded_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.assets
    ADD CONSTRAINT assets_uploaded_by_foreign FOREIGN KEY (uploaded_by) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- Name: client_project_requests client_project_requests_client_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.client_project_requests
    ADD CONSTRAINT client_project_requests_client_id_foreign FOREIGN KEY (client_id) REFERENCES public.clients(id) ON DELETE CASCADE;


--
-- Name: client_project_requests client_project_requests_project_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.client_project_requests
    ADD CONSTRAINT client_project_requests_project_id_foreign FOREIGN KEY (project_id) REFERENCES public.projects(id) ON DELETE SET NULL;


--
-- Name: clients clients_account_manager_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.clients
    ADD CONSTRAINT clients_account_manager_id_foreign FOREIGN KEY (account_manager_id) REFERENCES public.users(id);


--
-- Name: clients clients_branch_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.clients
    ADD CONSTRAINT clients_branch_id_foreign FOREIGN KEY (branch_id) REFERENCES public.branches(id);


--
-- Name: cms_pages cms_pages_updated_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.cms_pages
    ADD CONSTRAINT cms_pages_updated_by_foreign FOREIGN KEY (updated_by) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- Name: creative_jobs creative_jobs_client_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.creative_jobs
    ADD CONSTRAINT creative_jobs_client_id_foreign FOREIGN KEY (client_id) REFERENCES public.clients(id);


--
-- Name: creative_jobs creative_jobs_created_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.creative_jobs
    ADD CONSTRAINT creative_jobs_created_by_foreign FOREIGN KEY (created_by) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- Name: creative_jobs creative_jobs_current_workflow_stage_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.creative_jobs
    ADD CONSTRAINT creative_jobs_current_workflow_stage_id_foreign FOREIGN KEY (current_workflow_stage_id) REFERENCES public.workflow_stages(id) ON DELETE SET NULL;


--
-- Name: creative_jobs creative_jobs_job_category_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.creative_jobs
    ADD CONSTRAINT creative_jobs_job_category_id_foreign FOREIGN KEY (job_category_id) REFERENCES public.job_categories(id) ON DELETE SET NULL;


--
-- Name: creative_jobs creative_jobs_job_status_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.creative_jobs
    ADD CONSTRAINT creative_jobs_job_status_id_foreign FOREIGN KEY (job_status_id) REFERENCES public.job_statuses(id) ON DELETE SET NULL;


--
-- Name: creative_jobs creative_jobs_project_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.creative_jobs
    ADD CONSTRAINT creative_jobs_project_id_foreign FOREIGN KEY (project_id) REFERENCES public.projects(id) ON DELETE SET NULL;


--
-- Name: creative_jobs creative_jobs_project_manager_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.creative_jobs
    ADD CONSTRAINT creative_jobs_project_manager_id_foreign FOREIGN KEY (project_manager_id) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- Name: creative_jobs creative_jobs_traffic_manager_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.creative_jobs
    ADD CONSTRAINT creative_jobs_traffic_manager_id_foreign FOREIGN KEY (traffic_manager_id) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- Name: crm_activities crm_activities_company_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.crm_activities
    ADD CONSTRAINT crm_activities_company_id_foreign FOREIGN KEY (company_id) REFERENCES public.crm_companies(id) ON DELETE CASCADE;


--
-- Name: crm_activities crm_activities_contact_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.crm_activities
    ADD CONSTRAINT crm_activities_contact_id_foreign FOREIGN KEY (contact_id) REFERENCES public.crm_contacts(id) ON DELETE SET NULL;


--
-- Name: crm_activities crm_activities_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.crm_activities
    ADD CONSTRAINT crm_activities_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- Name: crm_companies crm_companies_owner_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.crm_companies
    ADD CONSTRAINT crm_companies_owner_id_foreign FOREIGN KEY (owner_id) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- Name: crm_contacts crm_contacts_company_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.crm_contacts
    ADD CONSTRAINT crm_contacts_company_id_foreign FOREIGN KEY (company_id) REFERENCES public.crm_companies(id) ON DELETE CASCADE;


--
-- Name: crm_tasks crm_tasks_assigned_to_foreign; Type: FK CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.crm_tasks
    ADD CONSTRAINT crm_tasks_assigned_to_foreign FOREIGN KEY (assigned_to) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- Name: crm_tasks crm_tasks_company_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.crm_tasks
    ADD CONSTRAINT crm_tasks_company_id_foreign FOREIGN KEY (company_id) REFERENCES public.crm_companies(id) ON DELETE SET NULL;


--
-- Name: email_intake_attachments email_intake_attachments_email_intake_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.email_intake_attachments
    ADD CONSTRAINT email_intake_attachments_email_intake_id_foreign FOREIGN KEY (email_intake_id) REFERENCES public.email_intakes(id) ON DELETE CASCADE;


--
-- Name: email_intake_traffic_members email_intake_traffic_members_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.email_intake_traffic_members
    ADD CONSTRAINT email_intake_traffic_members_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: email_intake_validations email_intake_validations_email_intake_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.email_intake_validations
    ADD CONSTRAINT email_intake_validations_email_intake_id_foreign FOREIGN KEY (email_intake_id) REFERENCES public.email_intakes(id) ON DELETE CASCADE;


--
-- Name: email_intakes email_intakes_creative_job_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.email_intakes
    ADD CONSTRAINT email_intakes_creative_job_id_foreign FOREIGN KEY (creative_job_id) REFERENCES public.creative_jobs(id) ON DELETE SET NULL;


--
-- Name: email_intakes email_intakes_reviewed_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.email_intakes
    ADD CONSTRAINT email_intakes_reviewed_by_foreign FOREIGN KEY (reviewed_by) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- Name: job_activities job_activities_creative_job_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.job_activities
    ADD CONSTRAINT job_activities_creative_job_id_foreign FOREIGN KEY (creative_job_id) REFERENCES public.creative_jobs(id) ON DELETE CASCADE;


--
-- Name: job_activities job_activities_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.job_activities
    ADD CONSTRAINT job_activities_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- Name: job_assignments job_assignments_assigned_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.job_assignments
    ADD CONSTRAINT job_assignments_assigned_by_foreign FOREIGN KEY (assigned_by) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- Name: job_assignments job_assignments_creative_job_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.job_assignments
    ADD CONSTRAINT job_assignments_creative_job_id_foreign FOREIGN KEY (creative_job_id) REFERENCES public.creative_jobs(id) ON DELETE CASCADE;


--
-- Name: job_assignments job_assignments_supervisor_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.job_assignments
    ADD CONSTRAINT job_assignments_supervisor_id_foreign FOREIGN KEY (supervisor_id) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- Name: job_assignments job_assignments_team_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.job_assignments
    ADD CONSTRAINT job_assignments_team_id_foreign FOREIGN KEY (team_id) REFERENCES public.teams(id) ON DELETE SET NULL;


--
-- Name: job_assignments job_assignments_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.job_assignments
    ADD CONSTRAINT job_assignments_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- Name: job_stage_histories job_stage_histories_creative_job_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.job_stage_histories
    ADD CONSTRAINT job_stage_histories_creative_job_id_foreign FOREIGN KEY (creative_job_id) REFERENCES public.creative_jobs(id) ON DELETE CASCADE;


--
-- Name: job_stage_histories job_stage_histories_entered_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.job_stage_histories
    ADD CONSTRAINT job_stage_histories_entered_by_foreign FOREIGN KEY (entered_by) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- Name: job_stage_histories job_stage_histories_workflow_stage_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.job_stage_histories
    ADD CONSTRAINT job_stage_histories_workflow_stage_id_foreign FOREIGN KEY (workflow_stage_id) REFERENCES public.workflow_stages(id) ON DELETE CASCADE;


--
-- Name: marketing_campaign_recipients marketing_campaign_recipients_campaign_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.marketing_campaign_recipients
    ADD CONSTRAINT marketing_campaign_recipients_campaign_id_foreign FOREIGN KEY (campaign_id) REFERENCES public.marketing_campaigns(id) ON DELETE CASCADE;


--
-- Name: marketing_campaign_recipients marketing_campaign_recipients_company_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.marketing_campaign_recipients
    ADD CONSTRAINT marketing_campaign_recipients_company_id_foreign FOREIGN KEY (company_id) REFERENCES public.crm_companies(id) ON DELETE SET NULL;


--
-- Name: marketing_campaign_recipients marketing_campaign_recipients_contact_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.marketing_campaign_recipients
    ADD CONSTRAINT marketing_campaign_recipients_contact_id_foreign FOREIGN KEY (contact_id) REFERENCES public.crm_contacts(id) ON DELETE SET NULL;


--
-- Name: marketing_campaigns marketing_campaigns_created_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.marketing_campaigns
    ADD CONSTRAINT marketing_campaigns_created_by_foreign FOREIGN KEY (created_by) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- Name: projects projects_client_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.projects
    ADD CONSTRAINT projects_client_id_foreign FOREIGN KEY (client_id) REFERENCES public.clients(id);


--
-- Name: projects projects_project_manager_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.projects
    ADD CONSTRAINT projects_project_manager_id_foreign FOREIGN KEY (project_manager_id) REFERENCES public.users(id);


--
-- Name: support_messages support_messages_ticket_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.support_messages
    ADD CONSTRAINT support_messages_ticket_id_foreign FOREIGN KEY (ticket_id) REFERENCES public.support_tickets(id) ON DELETE CASCADE;


--
-- Name: support_messages support_messages_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.support_messages
    ADD CONSTRAINT support_messages_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- Name: support_tickets support_tickets_assigned_to_foreign; Type: FK CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.support_tickets
    ADD CONSTRAINT support_tickets_assigned_to_foreign FOREIGN KEY (assigned_to) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- Name: support_tickets support_tickets_client_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.support_tickets
    ADD CONSTRAINT support_tickets_client_id_foreign FOREIGN KEY (client_id) REFERENCES public.clients(id) ON DELETE SET NULL;


--
-- Name: support_tickets support_tickets_company_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.support_tickets
    ADD CONSTRAINT support_tickets_company_id_foreign FOREIGN KEY (company_id) REFERENCES public.crm_companies(id) ON DELETE SET NULL;


--
-- Name: user_outlook_accounts user_outlook_accounts_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.user_outlook_accounts
    ADD CONSTRAINT user_outlook_accounts_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: users users_branch_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_branch_id_foreign FOREIGN KEY (branch_id) REFERENCES public.branches(id) ON DELETE SET NULL;


--
-- Name: users users_role_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_role_id_foreign FOREIGN KEY (role_id) REFERENCES public.roles(id) ON DELETE SET NULL;


--
-- Name: users users_team_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_team_id_foreign FOREIGN KEY (team_id) REFERENCES public.teams(id) ON DELETE SET NULL;


--
-- Name: workflow_transitions workflow_transitions_from_stage_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.workflow_transitions
    ADD CONSTRAINT workflow_transitions_from_stage_id_foreign FOREIGN KEY (from_stage_id) REFERENCES public.workflow_stages(id) ON DELETE CASCADE;


--
-- Name: workflow_transitions workflow_transitions_to_stage_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: mziyad
--

ALTER TABLE ONLY public.workflow_transitions
    ADD CONSTRAINT workflow_transitions_to_stage_id_foreign FOREIGN KEY (to_stage_id) REFERENCES public.workflow_stages(id) ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--

\unrestrict HfI4JExe45Se6WiEDph693jT8gKBZciLIEtRn6YPpPdJifEoZYB3FLhQU3w24sK

