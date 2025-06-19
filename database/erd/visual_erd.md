# Paageming - Visual ERD Diagram

## Logical ERD Diagram

```
┌─────────────────┐       ┌─────────────────┐       ┌─────────────────┐
│      USER       │       │    CATEGORY     │       │    PRODUCT      │
├─────────────────┤       ├─────────────────┤       ├─────────────────┤
│ user_id (PK)    │       │ category_id(PK) │       │ product_id (PK) │
│ name            │       │ name            │       │ category_id(FK) │
│ email           │       │ description     │       │ name            │
│ password        │       │ created_at      │       │ description     │
│ role            │       │ updated_at      │       │ price           │
│ created_at      │       └─────────────────┘       │ stock           │
│ updated_at      │                │                │ image_url       │
└─────────────────┘                │                │ status          │
         │                         │                │ created_at      │
         │                         │                │ updated_at      │
         │                         └──────────────┐ └─────────────────┘
         │                                        │          │
         │                                        ▼          │
         │                                    (1:Many)       │
         │                                                   │
         │                ┌─────────────────┐                │
         │                │      CART       │                │
         │                ├─────────────────┤                │
         │                │ cart_id (PK)    │                │
         │                │ user_id (FK)    │                │
         │                │ status          │                │
         │                │ created_at      │                │
         │                │ updated_at      │                │
         │                └─────────────────┘                │
         │                         │                         │
         │                         │                         │
         └──────────────┐          │          ┌──────────────┘
                        │          │          │
                        ▼          ▼          ▼
                    (1:Many)   (1:Many)   (1:Many)
                        │          │          │
         ┌─────────────────┐  ┌─────────────────┐  ┌─────────────────┐
         │     ORDER       │  │   CART_ITEM     │  │   ORDER_ITEM    │
         ├─────────────────┤  ├─────────────────┤  ├─────────────────┤
         │ order_id (PK)   │  │ item_id (PK)    │  │ item_id (PK)    │
         │ user_id (FK)    │  │ cart_id (FK)    │  │ order_id (FK)   │
         │ total_amount    │  │ product_id (FK) │  │ product_id (FK) │
         │ status          │  │ quantity        │  │ quantity        │
         │ shipping_addr   │  │ price_at_time   │  │ price_at_time   │
         │ payment_method  │  │ created_at      │  │ subtotal        │
         │ payment_status  │  │ updated_at      │  │ created_at      │
         │ notes           │  └─────────────────┘  │ updated_at      │
         │ created_at      │           │           └─────────────────┘
         │ updated_at      │           │                    │
         └─────────────────┘           │                    │
                  │                    │                    │
                  │                    └────────────────────┘
                  │                             │
                  └─────────────────────────────┘
                                (1:Many)
```

## Relationship Summary

```
USER (1) ──────────── (Many) CART
USER (1) ──────────── (Many) ORDER
CATEGORY (1) ───────── (Many) PRODUCT
CART (1) ──────────── (Many) CART_ITEM
ORDER (1) ─────────── (Many) ORDER_ITEM
PRODUCT (1) ────────── (Many) CART_ITEM
PRODUCT (1) ────────── (Many) ORDER_ITEM
```

## Data Flow Diagram

```
┌─────────────┐    ┌─────────────┐    ┌─────────────┐
│   ADMIN     │    │    USER     │    │   PRODUCT   │
│             │    │             │    │   CATALOG   │
└──────┬──────┘    └──────┬──────┘    └──────┬──────┘
       │                  │                  │
       ▼                  ▼                  ▼
┌─────────────┐    ┌─────────────┐    ┌─────────────┐
│   MANAGE    │    │   BROWSE    │    │   DISPLAY   │
│  PRODUCTS   │    │  PRODUCTS   │    │  PRODUCTS   │
│  CATEGORIES │    │             │    │             │
└──────┬──────┘    └──────┬──────┘    └──────┬──────┘
       │                  │                  │
       │                  ▼                  │
       │           ┌─────────────┐           │
       │           │  ADD TO     │           │
       │           │   CART      │           │
       │           └──────┬──────┘           │
       │                  │                  │
       │                  ▼                  │
       │           ┌─────────────┐           │
       │           │  CHECKOUT   │           │
       │           │   PROCESS   │           │
       │           └──────┬──────┘           │
       │                  │                  │
       │                  ▼                  │
       │           ┌─────────────┐           │
       │           │   CREATE    │           │
       │           │   ORDER     │           │
       │           └─────────────┘           │
       │                                     │
       └─────────────────────────────────────┘
                     │
                     ▼
              ┌─────────────┐
              │   FIREBASE  │
              │  REALTIME   │
              │  DATABASE   │
              └─────────────┘
```
