CREATE TABLE "businesses" (
  "id" uuid PRIMARY KEY,
  "name" varchar,
  "owner_name" varchar,
  "created_at" timestamp,
  "updated_at" timestamp
);

CREATE TABLE "outlets" (
  "id" uuid PRIMARY KEY,
  "business_id" uuid,
  "name" varchar,
  "address" varchar,
  "created_at" timestamp,
  "updated_at" timestamp
);

CREATE TABLE "users" (
  "id" uuid PRIMARY KEY,
  "business_id" uuid,
  "outlet_id" uuid,
  "name" varchar,
  "role" varchar,
  "created_at" timestamp,
  "updated_at" timestamp
);

CREATE TABLE "categories" (
  "id" uuid PRIMARY KEY,
  "business_id" uuid,
  "name" varchar
);

CREATE TABLE "products" (
  "id" uuid PRIMARY KEY,
  "business_id" uuid,
  "category_id" uuid,
  "name" varchar,
  "sku" varchar,
  "sell_price" decimal,
  "cost_price" decimal,
  "created_at" timestamp,
  "updated_at" timestamp
);

CREATE TABLE "membership_tiers" (
  "id" uuid PRIMARY KEY,
  "business_id" uuid,
  "name" varchar
);

CREATE TABLE "customers" (
  "id" uuid PRIMARY KEY,
  "business_id" uuid,
  "membership_tier_id" uuid,
  "name" varchar,
  "phone" varchar,
  "created_at" timestamp
);

CREATE TABLE "discounts" (
  "id" uuid PRIMARY KEY,
  "business_id" uuid,
  "membership_tier_id" uuid,
  "name" varchar,
  "type" varchar,
  "scope" varchar,
  "target_id" uuid,
  "value_type" varchar,
  "value" decimal,
  "start_date" date,
  "end_date" date,
  "is_combinable" boolean,
  "is_active" boolean
);

CREATE TABLE "stocks" (
  "id" uuid PRIMARY KEY,
  "product_id" uuid,
  "outlet_id" uuid,
  "qty" int,
  "updated_at" timestamp
);

CREATE TABLE "transactions" (
  "id" uuid PRIMARY KEY,
  "outlet_id" uuid,
  "user_id" uuid,
  "customer_id" uuid,
  "discount_id" uuid,
  "discount_amount" decimal,
  "total" decimal,
  "local_id" uuid,
  "synced_at" timestamp,
  "created_at" timestamp
);

CREATE TABLE "transaction_items" (
  "id" uuid PRIMARY KEY,
  "transaction_id" uuid,
  "product_id" uuid,
  "qty" int,
  "price" decimal
);

CREATE TABLE "cost_categories" (
  "id" uuid PRIMARY KEY,
  "name" varchar,
  "type" varchar
);

CREATE TABLE "operational_costs" (
  "id" uuid PRIMARY KEY,
  "outlet_id" uuid,
  "category_id" uuid,
  "amount" decimal,
  "period" varchar,
  "created_at" timestamp
);

COMMENT ON COLUMN "users"."outlet_id" IS 'null untuk admin/owner yang akses lintas outlet';

COMMENT ON COLUMN "users"."role" IS 'admin | manager_outlet | kasir';

COMMENT ON COLUMN "products"."category_id" IS 'nullable';

COMMENT ON COLUMN "membership_tiers"."name" IS 'contoh: Silver, Gold';

COMMENT ON COLUMN "customers"."membership_tier_id" IS 'null = bukan member';

COMMENT ON COLUMN "discounts"."membership_tier_id" IS 'diisi kalau type = membership';

COMMENT ON COLUMN "discounts"."type" IS 'membership | event';

COMMENT ON COLUMN "discounts"."scope" IS 'total | product | category';

COMMENT ON COLUMN "discounts"."target_id" IS 'id produk/kategori kalau scope bukan total; null kalau total';

COMMENT ON COLUMN "discounts"."value_type" IS 'percentage | fixed';

COMMENT ON COLUMN "discounts"."start_date" IS 'null untuk diskon membership yang terus berlaku';

COMMENT ON COLUMN "discounts"."is_combinable" IS 'boleh digabung dengan diskon lain atau tidak';

COMMENT ON COLUMN "transactions"."customer_id" IS 'nullable, null = customer umum non-member';

COMMENT ON COLUMN "transactions"."discount_id" IS 'diskon yang otomatis dipilih sistem, nullable';

COMMENT ON COLUMN "transactions"."discount_amount" IS 'nominal diskon yang diterapkan';

COMMENT ON COLUMN "transactions"."local_id" IS 'UUID dibuat di device mobile saat transaksi (termasuk offline), dipakai cegah duplikat pas sync';

COMMENT ON COLUMN "transactions"."synced_at" IS 'null = belum tersinkron dari mobile offline';

COMMENT ON COLUMN "cost_categories"."type" IS 'fixed | variable';

COMMENT ON COLUMN "operational_costs"."period" IS 'contoh: 2026-08 untuk periode bulanan';

ALTER TABLE "outlets" ADD FOREIGN KEY ("business_id") REFERENCES "businesses" ("id") DEFERRABLE INITIALLY IMMEDIATE;

ALTER TABLE "users" ADD FOREIGN KEY ("business_id") REFERENCES "businesses" ("id") DEFERRABLE INITIALLY IMMEDIATE;

ALTER TABLE "users" ADD FOREIGN KEY ("outlet_id") REFERENCES "outlets" ("id") DEFERRABLE INITIALLY IMMEDIATE;

ALTER TABLE "categories" ADD FOREIGN KEY ("business_id") REFERENCES "businesses" ("id") DEFERRABLE INITIALLY IMMEDIATE;

ALTER TABLE "products" ADD FOREIGN KEY ("business_id") REFERENCES "businesses" ("id") DEFERRABLE INITIALLY IMMEDIATE;

ALTER TABLE "products" ADD FOREIGN KEY ("category_id") REFERENCES "categories" ("id") DEFERRABLE INITIALLY IMMEDIATE;

ALTER TABLE "membership_tiers" ADD FOREIGN KEY ("business_id") REFERENCES "businesses" ("id") DEFERRABLE INITIALLY IMMEDIATE;

ALTER TABLE "customers" ADD FOREIGN KEY ("business_id") REFERENCES "businesses" ("id") DEFERRABLE INITIALLY IMMEDIATE;

ALTER TABLE "customers" ADD FOREIGN KEY ("membership_tier_id") REFERENCES "membership_tiers" ("id") DEFERRABLE INITIALLY IMMEDIATE;

ALTER TABLE "discounts" ADD FOREIGN KEY ("business_id") REFERENCES "businesses" ("id") DEFERRABLE INITIALLY IMMEDIATE;

ALTER TABLE "discounts" ADD FOREIGN KEY ("membership_tier_id") REFERENCES "membership_tiers" ("id") DEFERRABLE INITIALLY IMMEDIATE;

ALTER TABLE "stocks" ADD FOREIGN KEY ("product_id") REFERENCES "products" ("id") DEFERRABLE INITIALLY IMMEDIATE;

ALTER TABLE "stocks" ADD FOREIGN KEY ("outlet_id") REFERENCES "outlets" ("id") DEFERRABLE INITIALLY IMMEDIATE;

ALTER TABLE "transactions" ADD FOREIGN KEY ("outlet_id") REFERENCES "outlets" ("id") DEFERRABLE INITIALLY IMMEDIATE;

ALTER TABLE "transactions" ADD FOREIGN KEY ("user_id") REFERENCES "users" ("id") DEFERRABLE INITIALLY IMMEDIATE;

ALTER TABLE "transactions" ADD FOREIGN KEY ("customer_id") REFERENCES "customers" ("id") DEFERRABLE INITIALLY IMMEDIATE;

ALTER TABLE "transactions" ADD FOREIGN KEY ("discount_id") REFERENCES "discounts" ("id") DEFERRABLE INITIALLY IMMEDIATE;

ALTER TABLE "transaction_items" ADD FOREIGN KEY ("transaction_id") REFERENCES "transactions" ("id") DEFERRABLE INITIALLY IMMEDIATE;

ALTER TABLE "transaction_items" ADD FOREIGN KEY ("product_id") REFERENCES "products" ("id") DEFERRABLE INITIALLY IMMEDIATE;

ALTER TABLE "operational_costs" ADD FOREIGN KEY ("outlet_id") REFERENCES "outlets" ("id") DEFERRABLE INITIALLY IMMEDIATE;

ALTER TABLE "operational_costs" ADD FOREIGN KEY ("category_id") REFERENCES "cost_categories" ("id") DEFERRABLE INITIALLY IMMEDIATE;
